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
    protected $fillable = ['item_id', 'warehouse_id', 'warehouse_position_id', 'all_quantity', 'available_quantity', 'hold_quantity', 'amount', 'created_at'];

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
     *  @return
     *
     */
    public function stockIn()
    {
        return $this->hasMany('App\Models\Stock\InModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two model 
     *
     *  @return
     *
     */
    public function stockOut()
    {
        return $this->hasMany('App\Models\Stock\OutModel', 'stock_id', 'id');
    }

    /**
     * the api of stock in
     *  
     * @param $arr 
     * the keys in order
     * 'item_id'=>item号,
     * 'quantity' => '数量',
     * 'amount' => '总金额',
     * 'warehouse_positions_id' => '库位id',
     * 'type' => '入库类型',
     * 'relation_id' => '入库来源id',
     * 'remark' => '备注'
     * @return none
     *
     */
    // 
    /**public function in($arr)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $model = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id'], 'items_id'=>$arr['items_id']])->get();
    //         $obj = $model->first();
    //         if($obj) {
    //             $obj->all_quantity += $arr['quantity'];
    //             $obj->available_quantity +=$arr['quantity'];
    //             $obj->amount +=$arr['amount'];
    //             $obj->save();
    //             $arr['stock_id'] = $obj->id;
    //             $obj->stockIn()->create($arr);
    //         } else {
    //             $warehouses_id = PositionModel::find($arr['warehouse_positions_id'])->warehouses_id;
    //             $len = StockModel::where(['warehouses_id'=>$warehouses_id, 'items_id'=>$arr['items_id']])->get()->count();
    //             if($len >= 2)
    //                 throw new Exception('sku对应库位最大数量超过了2个');
    //             $tmp = $this->create($arr);
    //             $tmp->all_quantity = $arr['quantity'];
    //             $tmp->available_quantity = $arr['quantity'];
    //             $tmp->save();
    //             $arr['stock_id'] = $tmp->id;
    //             $tmp->stockIn()->create($arr);
    //         }
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         throw new Exception('入库出错,可能的原因是sku对应的库位数超过了2,请检查入库库位');
    //     }
    //     DB::commit();
    // }

    // /**
    //  * the api of stock out | similar to the stock in
    //  *  
    //  * @param $arr 
    //  * the keys in order
    //  * 'items_id'=>item号,
    //  * 'quantity' => '数量',
    //  * 'amount' => '总金额',
    //  * 'warehouse_positions_id' => '库位id',
    //  * 'type' => '入库类型',
    //  * 'relation_id' => '入库来源id',
    //  * 'remark' => '备注'
    //  * @return none
    //  *
    //  */
    // public function out($arr)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id'], 'items_id'=>$arr['items_id']])->get()->first();
    //         if($obj) {
    //             $obj->all_quantity -=$arr['quantity'];
    //             $obj->available_quantity -= $arr['quantity'];
    //             $obj->amount -= $arr['amount'];
    //             if($obj->available_quantity < 0 || $obj->amount < 0) {
    //                 throw new Exception('库存数量和金额为负');
    //             }
    //             $obj->save();
    //             $arr['stock_id'] = $obj->id;
    //             $obj->stockOut()->create($arr);
    //         }
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         throw new Exception('出库出错,可能的原因是库存数量或金额被减成负的了');
    //     }
    //     DB::commit();
    // }

    // /**
    //  * the api of stock hold | similar to the stock in
    //  *
    //  * arr[]参数列表
    //  * 'items_id'=>item号
    //  * 'warehouse_positions_id'=> 库位号
    //  * 'quantity'=>数量
    //  *
    //  * $flag bool 1表示hold 0表示反Hold
    //  *  @return none
    //  *
    //  */
    // public function hold($arr, $flag) 
    // {
    //     DB::beginTransaction();
    //     try {
    //         $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id'], 'items_id'=>$arr['items_id']])->get()->first();
    //         if($flag && $obj) {
    //             $obj->available_quantity -= $arr['quantity'];
    //             $obj->hold_quantity += $arr['quantity'];
    //             if($obj->available_quantity < 0) {
    //                 throw new Exception('可用数量是负的了');
    //             }
    //             $obj->save();
    //         } else {
    //             $obj->available_quantity += $arr['quantity'];
    //             $obj->hold_quantity -= $arr['quantity'];
    //             if($obj->hold_quantity < 0) {
    //                 throw new Exception('hold数量是负的了');
    //             }
    //             $obj->save();
    //         }
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         throw new Exception('hold出错,hold数量变成负的了');
    //     }
    //     DB::commit();
    // } 
    /*  
     * return the the relation ship between the two model
     *
     *  @return model
     *
     */
    public function items()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
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
        for($i=0; $i < count($obj); $i++)
        {
            $money += $obj[$i]['amount'];
            $amount += $obj[$i]['all_quantity'];
        }

        return round($money/$amount, 3);
    }

    public function in($quantity, $amount, $type, $relation_id, $remark)
    {
        $this->all_quantity += $quantity;
        $this->available_quantity += $quantity;
        $this->amount += $amount;
        $this->save();
        $this->stockIn()->create(['quantity'=>$quantity, 'amount'=>$amount, 'type'=>$type, 'relation_id'=>$relation_id, 'remark'=>$remark]);
    }

    // public function createNew($item_id, $warehoustPosistionId)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $warehouseId = PositionModel::where(['id', $warehousePositionId])->first()->warehouse_id;
    //         $stock = $this->where(['item_id'=>$item_id, 'warehouse_id'=>$warehouseId])->get();
    //         $len = $stock->count();

    //         return $stock;
    //         if($len >= 2)
    //             throw new Exception();
    //         $stock = $this->create(['item_id'=>$item_id, 'warehouse_id'=>$warehouseId, 'warehouse_position_id'=>$warehousePositionId]);
    //         return $stock;
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         throw new Exception('sku对应库位最大数量超过了2个');
    //     }
    // }

    public function hold($quantity)
    {
        $this->available_quantity -= $quantity;
        if($this->available_quantity < 0)
            throw new Exception('hold时，可用数量为负了');
        $this->hold_quantity += $quantity;
        $this->save();
    }

    public function unhold($quantity)
    {
        $this->hold_quantity -= $quantity;
        if($this->hold_quantity < 0)
            throw new Exception('unhold时，hold数量为负了');
        $this->available_quantity += $quantity;
        $this->save();
    }

    public function out($quantity, $type, $relation_id, $remark)
    {
        $this->all_quantity -= $quantity;
        $this->available_quantity -= $quantity;
        $this->amount -= $quantity*$this->unit_cost;
        if($this->available_quantity < 0 || $this->amount < 0) {
            throw new Exception('出库时数量和金额有问题');
        }
        $this->save();
        $this->stockOut()->create(['quantity'=>$quantity, 'amount'=>$quantity*$this->unit_cost, 'type'=>$type, 'relation_id'=>$relation_id, 'remark'=>$remark]);
    }
}
