<?php

namespace App\Models;

use DB;
use Exception;
use App\Base\BaseModel;
use App\Models\Package\ItemModel;
use App\Models\Pick\ListItemModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\Pick\PackageScoreModel;
use App\Models\Warehouse\PositionModel;

class PickListModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'picklists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picklist_id', 'type', 'status', 'logistic_id', 'pick_by', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['picklist_id'];

    public function pickListItem()
    {
        return $this->hasMany('App\Models\Pick\ListItemModel', 'picklist_id', 'id');
    }

    public function package()
    {
        return $this->hasMany('App\Models\PackageModel', 'picklist_id', 'id');
    }

    public function logistic()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistic_id', 'id');
    }

    public function pickByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'pick_by', 'id');
    }

    /**
     * 接受packages，对应相应的操作,单单单多/多多
     * 
     * @param $packages 满足条件的包裹
     * @return none
     *
     */
    public function createPickListItems($packages)
    {
        foreach($packages as $package)
        {
            if($package->type != 'MULTI') {
                $this->createListItems($package);
            } else {
                $score = $this->getScore($package);
                PackageScoreModel::create(['package_id'=>$package->id, 'package_score'=>$score]);
            }
        }
    }

    /**
     * 生成pickListItems
     *
     * @param $package 包裹
     * @return none
     *
     */
    public function createListItems($package)
    {
        foreach($package->items as $packageitem)
        {
            $stock = new StockModel;
            $arr = $stock->allocateStock($packageitem->item_id, $packageitem->quantity);
            if(!$arr) {
                throw new Exception('id为'.$package->id.'的package中有未能分配到库存的项');
            }
            foreach($arr as $key => $value) {
                $query = ListItemModel::where(['type'=>$package->type, 'item_id'=>$packageitem->item_id, 'warehouse_position_id'=>$value[0], 'picklist_id'=>'0'])->first();
                if(!$query) {
                    $obj = ListItemModel::create(['type'=>$package->type, 'item_id'=>$packageitem->item_id, 'warehouse_position_id'=>$value[0], 'quantity'=>$value[1]]);
                    $obj->pickListItemPackage()->create(['package_id' => $package->id]);
                } else {
                    $query->quantity += $value[1];
                    $query->save();
                    $query->pickListItemPackage()->create(['package_id' => $package->id]);
                }
                $stock->where(['item_id'=>$packageitem->item_id, 'warehouse_position_id'=>$value[0]])->first()->hold($value[1]);
            }   
        }
    }

    /**
     * 获取某个包裹得分
     *
     * @param $package 包裹
     * @return score integer
     *
     */
    public function getScore($package)
    {
        $buf = [];
        foreach($package->items as $packageitem)
        {
            $stock = new StockModel;
            $arr = $stock->allocateStock($packageitem->item_id, $packageitem->quantity);
            if(!$arr) {
                throw new Exception('id为'.$package->id.'的package中有未能分配到库存的项');
            }
            foreach($arr as $key => $value)
            {
                $name = PositionModel::find($value[0])->name;
                $tmp = substr($name,1,1);
                $buf[] = $tmp;
            }
        }
        $buf = array_unique($buf);
        $num = 0;
        foreach($buf as $value)
        {
            $num += pow(2,abs(ord($value)-ord('A')));
        }
        
        return $num;
    }

    /**
     * 生成pickList ,非混合物流
     *
     * @param $listItemQuantity SINGLE/SINGLEMULTI 拣货单上的条目个数
     * @param $multiQuantity MULTI 同上 | $logistic_id 物流
     *
     * @return none
     */
    public function createPickList($listItemQuantity, $multiQuantity, $logistic_id)
    {
        srand(time());
        $query = ListItemModel::where(['picklist_id'=>'0', 'type'=>'SINGLE']);
        if($query->count()) {
            $picklists = $query->orderBy('warehouse_position_id')->get()->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'SINGLE', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                }
            }
        }
        $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLEMULTI']);
        if($query->count()) {
            $picklists = $query->orderBy('warehouse_position_id')->get()->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'SINGLEMULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                }
            }
        }
        $query = PackageScoreModel::where(['picklist_id'=>'0']);
        if($query->count()) {            
            $packageScores = $query->orderBy('package_score')->get()->chunk($multiQuantity);
            foreach($packageScores as $packageScore) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'MULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($packageScore as $score)
                {
                    $score->picklist_id = $obj->id;
                    $score->save();
                    $this->createListItems(PackageModel::find($score->package_id));
                    $package = PackageModel::find($score->package_id);
                    $package->picklist_id = $obj->id;
                    $package->status = 'PICKING';
                    $package->save();
                }
                $this->setPickListId($obj->id);
            }
        }
    }

    /**
     * 生成pickList ,混合物流
     *
     * @param $listItemQuantity SINGLE/SINGLEMULTI 拣货单上的条目个数
     * @param $multiQuantity MULTI 同上 | $logistic_id 物流
     *
     * @return none
     */
    public function createPickListFb($listItemQuantity, $multiQuantity)
    {
        srand(time());
        $query = ListItemModel::where(['picklist_id'=>'0', 'type'=>'SINGLE']);
        if($query->count()) {
            $picklists = $query->orderBy('warehouse_position_id')->get()->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'SINGLE', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>'0']);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                }
            }
         }
        $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLEMULTI']);
        if($query->count()) {
            $picklists = $query->orderBy('warehouse_position_id')->get()->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'SINGLEMULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>'0']);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                }
            }
        }
        $query = PackageScoreModel::where(['picklist_id'=>'0']);
        if($query->count()) {            
            $packageScores = $query->orderBy('package_score')->get()->chunk($multiQuantity);
            foreach($packageScores as $packageScore) {
                $obj = $this->create(['picklist_id'=>'pk'.rand()%10000000, 'type'=>'MULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>'0']);
                foreach($packageScore as $score)
                {
                    $score->picklist_id = $obj->id;
                    $score->save();
                    $this->createListItems(PackageModel::find($score->package_id));
                    $package = PackageModel::find($score->package_id);
                    $package->picklist_id = $obj->id;
                    $package->status = 'PICKING';
                    $package->save();
                }
                $this->setPickListId($obj->id);
            }
        }
    }

    /**
     * 设置picklist_id 
     *
     * @param $id integer
     * @return none
     *
     */
    public function setPickListId($id)
    {
        $pickListItems = ListItemModel::where(['picklist_id'=>'0', 'type'=>'MULTI'])->get();
        foreach($pickListItems as $pickListItem)
        {
            $pickListItem->picklist_id = $id;
            $pickListItem->save();
        }
    }

    /**
     * 获取器,status_name 
     *
     * @param none
     * 
     */
    public function getStatusNameAttribute()
    {
        $arr = config('pick.pick');
        return $arr[$this->status];
    }
}
