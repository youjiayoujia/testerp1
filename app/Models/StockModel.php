<?php

namespace App\Models;

use DB;
use Exception;
use App\Base\BaseModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;

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
    protected $fillable = ['items_id', 'warehouses_id', 'warehouse_positions_id', 'all_quantity', 'available_quantity', 'hold_quantity', 'amount', 'created_at'];

    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'all_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'hold_quantity' => 'required|integer',
            'amount' => 'required|numeric',    
        ],
        'update' => [
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
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
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two model 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
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
    public function in($arr)
    {
        DB::beginTransaction();
        try {
            $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id'], 'items_id'=>$arr['items_id']])->get()->first();
            if($obj) {
                $obj->all_quantity += $arr['quantity'];
                $obj->available_quantity +=$arr['quantity'];
                $obj->amount +=$arr['amount'];
                $obj->save();
                $arr['stock_id'] = $obj->id;
                $obj->stockIn()->create($arr);
            } else {
                $tmp = $this->create($arr);
                $tmp->all_quantity = $arr['quantity'];
                $tmp->available_quantity = $arr['quantity'];
                $tmp->save();
                $arr['stock_id'] = $tmp->id;
                $tmp->stockIn()->create($arr);
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();
    }

    /**
     * the api of stock out | similar to the stock in
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
    public function out($arr)
    {
        DB::beginTransaction();
        try {
            $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id'], 'items_id'=>$arr['items_id']])->get()->first();
            if($obj) {
                $obj->all_quantity -=$arr['quantity'];
                $obj->available_quantity -= $arr['quantity'];
                $obj->amount -= $arr['amount'];
                if($obj->available_quantity < 0 || $obj->amount < 0) {
                    throw new Exception('数量或金额有误');
                }
                $obj->save();
                $arr['stock_id'] = $obj->id;
                $obj->stockOut()->create($arr);
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();
    }
    
    /**
     * return the the relation ship between the two model
     *
     *  @return model
     *
     */
    public function items()
    {
        return $this->belongsTo('App\Models\ItemModel', 'items_id', 'id');
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
        $obj = $this->where('items_id', $this->items_id)->get()->toArray();
        $money = '';
        $amount = '';
        for($i=0; $i < count($obj); $i++)
        {
            $money += $obj[$i]['amount'];
            $amount += $obj[$i]['all_quantity'];
        }

        return round($money/$amount, 3);
    }
}
