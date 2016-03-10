<?php

namespace App\Models;

use App\Base\BaseModel;

class PickModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'picks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pick_id', 'status', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['pick_id'];

    public function getddanPickListArray($item_id, &$arr)
    {
        if(array_key_exists($item_id, $arr)) {
            $arr[$item_id][0] +=1;
        } else {
            $arr[$item_id][0] = 1;
        }
        $arr[$item_id][] = $package->id;
    }

    public function getdduoPickListArray($item_id, $quantity, &$arr)
    {
        if(array_key_exists($item_id, $arr)) {
            $arr[$item_id][0] +=$quantity;
        } else {
            $arr[$item_id][0] = $quantity;
        }
        $arr[$item_id][] = $package->id;
    }

    public function createdd($arr)
    {
        $pick = PickModel::create(['type'=>'dandan', 'pick_id'=>'pk'.time()]);
        foreach($arr as $key => $value)
        {
            $picklist = $pick->picklist()->create(['item_id'=>$key, 'quantity'=>$arr[$key][0]]);
            foreach($valu as $key => $v)
            {
                if($key != 0)
                {
                    $picklist->pickListToPackage()->create(['package_id'=>$v]);
                }
            }
        }
    }

    public function createddduo($arr)
    {
        $pick = PickModel::create(['type'=>'danduo', 'pick_id'=>'pk'.time()]);
        foreach($arr as $key => $value)
        {
            $picklist = $pick->picklist()->create(['item_id'=>$key, 'quantity'=>$arr[$key][0]]);
            foreach($valu as $key => $v)
            {
                if($key != 0)
                {
                    $picklist->pickListToPackage()->create(['package_id'=>$v]);
                }
            }
        }
    }

    public function createddduoAssistant($arr, $buf)
    {
        $pick = PickModel::create(['type'=>'duoduo', 'pick_id'=>'pk'.time()]);
        foreach($arr as $key => $val)
        {
            $pick->pickPackageScore()->create(['package_id'=>$key, 'package_score'=>$val]);
        }
        foreach($buf as $key => $value)
        {
            $picklist = $pick->picklist()->create(['item_id'=>$key, 'quantity'=>$buf[$key][0]]);
        }
    }

    public function createduoduo($arr)
    {
        $buf = [];
        foreach($arr as $key => $value)
        {   
            $orderitems = PackageModel::find($key)->orderitem();
            foreach($orderitems as $orderitem)
            {
                $this->getdduoPickListArray($orderitem->items->item_id, $orderitem->quantity, $buf);
            }
        }
    }


}
