<?php

namespace App\Models;

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
                    $obj->pickListItemPackage()->create(['package_id' => $package->id]);
                }
                $stock->where(['item_id'=>$packageitem->item_id, 'warehouse_position_id'=>$value[0]])->first()->hold($value[1]);
            }   
        }
    }

    public function getScore($package)
    {
        $buf = [];
        foreach($package->items as $packageitem)
        {
            $stock = new StockModel;
            $arr = $stock->allocateStock($packageitem->item_id, $packageitem->quantity);
            if(!$arr) {
                continue;
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

    public function createPickList($listItemQuantity, $multiQuantity, $logistic_id)
    {
        $query = ListItemModel::where('picklist_id','0');
        if($query->where('type','SINGLE')->count()) {
            $query->where('type', 'SINGLE')->orderBy('warehouse_position_id')->chunk($listItemQuantity, function($picklistItems) use ($logistic_id){
                $obj = $this->create(['picklist_id'=>'pk'.time(), 'type'=>'SINGLE', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($picklistItems as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                    $picklistItem->save();
                }
            });
         }
        $query = ListItemModel::where('picklist_id','0');
        if($query->where('type','SINGLEMULTI')->count()) {
            $query->where('type', 'SINGLEMULTI')->orderBy('warehouse_position_id')->chunk($listItemQuantity, function($picklistItems) use ($logistic_id){
                $obj = $this->create(['picklist_id'=>'pk'.time().'1', 'type'=>'SINGLEMULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($picklistItems as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    foreach($picklistItem->pickListItemPackage as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                    }
                    $picklistItem->save();
                }
            });
        }
        $query = PackageScoreModel::where('picklist_id','0');
        if($query->count()) {
            $query->orderBy('package_score')->chunk($multiQuantity, function($packageScores) use ($logistic_id){
                $obj = $this->create(['picklist_id'=>'pk'.time().'1', 'type'=>'MULTI', 'status'=>'NONE', 'pick_by'=>'1', 'logistic_id'=>$logistic_id]);
                foreach($packageScores as $packageScore)
                {
                    $packageScore->picklist_id = $obj->id;
                    $packageScore->save();
                    $this->createListItems(PackageModel::find($packageScore->package_id));
                    $package = PackageModel::find($packageScore->package_id);
                    $package->picklist_id = $obj->id;
                    $package->status = 'PICKING';
                    $package->save();
                }
                $this->setPickListId($obj->id);
            });
        }
    }

    public function setPickListId($id)
    {
        $pickListItems = ListItemModel::where(['picklist_id'=>'0', 'type'=>'MULTI'])->get();
        foreach($pickListItems as $pickListItem)
        {
            $pickListItem->picklist_id = $id;
            $pickListItem->save();
        }
    }

    public function getStatusNameAttribute()
    {
        $arr = config('pick.pick');
        return $arr[$this->status];
    }
}
