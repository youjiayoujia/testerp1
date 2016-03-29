<?php

namespace App\Models;

use DB;
use Exception;
use App\Base\BaseModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;
use App\Models\Warehouse\PositionModel;

class StockModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'warehouse_position_id',
        'all_quantity',
        'available_quantity',
        'hold_quantity',
        'amount',
        'created_at'
    ];

    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'warehouse_id' => 'required|integer',
            'warehouse_position_id' => 'required|integer',
            'all_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'hold_quantity' => 'required|integer',
            'amount' => 'required|numeric',
        ],
        'update' => [
            'warehouse_id' => 'required|integer',
            'warehouse_position_id' => 'required|integer',
            'all_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'hold_quantity' => 'required|integer',
            'amount' => 'required|numeric',
        ],
    ];

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockIn()
    {
        return $this->hasMany('App\Models\Stock\InModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockOut()
    {
        return $this->hasMany('App\Models\Stock\OutModel', 'stock_id', 'id');
    }

    /**
     * return the relation ship
     *
     * @return relation
     *
     */
    public function items()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function stockTakingForm()
    {
        return $this->hasOne('App\Models\Stock\TakingFormModel', 'stock_id', 'id');
    }
    /**
     * add additional attribute according to sku ,get the goods unit cost
     *
     * @param none
     * @return json
     *
     */
    public function getUnitCostAttribute()
    {
        $obj = $this->where('item_id', $this->item_id)->get()->toArray();
        $money = '';
        $amount = '';
        for ($i = 0; $i < count($obj); $i++) {
            $money += $obj[$i]['amount'];
            $amount += $obj[$i]['all_quantity'];
        }

        return round($money / $amount, 3);
    }

    /**
     * in api
     * @param
     * $quantity 数量
     * $amount 金额
     * $type 入库类型
     * $relation_id   例:调整表的某个id
     * $remark 备注
     *
     * @return none
     *
     */
    public function in($quantity, $amount, $type, $relation_id, $remark)
    {
        $this->all_quantity += $quantity;
        $this->available_quantity += $quantity;
        $this->amount += $amount;
        $this->save();
        $this->stockIn()->create([
            'quantity' => $quantity,
            'amount' => $amount,
            'type' => $type,
            'relation_id' => $relation_id,
            'remark' => $remark
        ]);
    }

    /**
     * hold api
     * @param
     * $quantity 数量
     *
     * @return none
     *
     */
    public function hold($quantity)
    {
        $this->available_quantity -= $quantity;
        if ($this->available_quantity < 0) {
            throw new Exception('hold时，可用数量为负了');
        }
        $this->hold_quantity += $quantity;
        $this->save();
    }

    /**
     * unhold api
     * @param
     * $quantity 数量
     *
     * @return none
     *
     */
    public function unhold($quantity)
    {
        $this->hold_quantity -= $quantity;
        if ($this->hold_quantity < 0) {
            throw new Exception('unhold时，hold数量为负了');
        }
        $this->available_quantity += $quantity;
        $this->save();
    }

    /**
     * in api
     * @param
     * $quantity 数量
     * $type 入库类型
     * $relation_id   例:调整表的某个id
     * $remark 备注
     *
     * @return none
     *
     */
    public function out($quantity, $type, $relation_id, $remark)
    {
        $this->all_quantity -= $quantity;
        $this->available_quantity -= $quantity;
        $this->amount -= $quantity * $this->unit_cost;
        if ($this->available_quantity < 0 || $this->amount < 0) {
            throw new Exception('出库时数量和金额有问题');
        }
        $this->save();
        $this->stockOut()->create([
            'quantity' => $quantity,
            'amount' => $quantity * $this->unit_cost,
            'type' => $type,
            'relation_id' => $relation_id,
            'remark' => $remark
        ]);
    }

    /**
     * 通过item_id和quantity自动分配库位 
     *
     * @param $item_id $quantity
     * @return array
     *
     */
    public function allocateStock($item_id, $quantity) 
    {
        $stocks = $this->where(['item_id'=>$item_id])->get();
        foreach($stocks as $stock)
        {
            if($stock->available_quantity > $quantity) {
                return [[$stock->warehouse_position_id, $quantity, $stock->id]];
            }
        }
        $arr = [];
        foreach($stocks as $stock)
        {
            if($stock->available_quantity < $quantity) {
                $quantity -= $stock->available_quantity;
                $arr[] = [$stock->warehouse_position_id, $stock->available_quantity, $stock->id];
            } else {
                $arr[] = [$stock->warehouse_position_id, $quantity, $stock->id];
                $quantity -= $quantity;
            }
        }
        if($quantity != 0) {
            return 'false';
        }
        if($arr) {
            return $arr;
        } else {
            return 'false';
        }
    }
}
