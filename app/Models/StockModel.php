<?php

namespace App\Models;

use DB;
use App\Base\BaseModel;
use App\Models\StockModel;
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
    protected $fillable = ['item_id', 'sku', 'warehouses_id', 'warehouse_positions_id', 'all_amount', 'available_amount', 'hold_amount', 'total_amount', 'created_at'];

    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required',
            'warehouses_id' => 'required|numeric',
            'warehouse_positions_id' => 'required|numeric',
            'all_amount' => 'required|numeric',
            'available_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',    
        ],
        'update' => [
            'sku' => 'required',
            'warehouses_id' => 'required|numeric',
            'warehouse_positions_id' => 'required|numeric',
            'all_amount' => 'required|numeric',
            'available_amount' => 'required|numeric',
            'total_amount' => 'required|numeric', 
        ],
    ];
    
    /**
     * get the relationship between the two module
     *
     * @return 
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }

    /**
     *  get the price of the goods 
     *
     *  @param $sku
     *  @return price
     *
     */
    public function getUnitCost($sku)
    {
        $stock = $this->where('sku', $sku)->first();
    
        return $stock->unit_cost;
    }

    /**
     * get the array of object by the param $arr 
     * 
     * @return object array
     *
     */
    public function getObj($arr, $field=['*'])
    {
        return $this->where($arr)->get($field);
    }

    /**
     * the api of stock in
     *  
     * @param $arr 
     * the keys in order
     * 'item_id'=>item号,
     * 'sku' => sku,
     * 'amount' => '数量',
     * 'total_amount' => '总金额',
     * 'warehouses_id' => '仓库id',
     * 'warehouse_positions_id' => '库位id',
     * 'type' => '入库类型',
     * 'relation_id' => '入库来源id',
     * 'remark' => '备注'
     * @return which the stock object
     *
     */
    public function in($arr)
    {
        $in = new InModel;
        DB::beginTransaction();
        try {
            $in->create($arr);
            $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id']])->get()->first();
            if($obj) {
                $obj->all_amount += $arr['amount'];
                $obj->available_amount +=$arr['amount'];
                $obj->total_amount +=$arr['total_amount'];
                $obj->save();
            } else {
                $tmp = $this->create($arr);
                $tmp->all_amount = $arr['amount'];
                $tmp->available_amount = $arr['amount'];
                $tmp->save();
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();

        return $obj;
    }

    /**
     * the api of stock out | similar to the stock in
     *  
     * @param $arr 
     * the keys in order
     * 'item_id'=>item号,
     * 'sku' => sku,
     * 'amount' => '数量',
     * 'total_amount' => '总金额',
     * 'warehouses_id' => '仓库id',
     * 'warehouse_positions_id' => '库位id',
     * 'type' => '入库类型',
     * 'relation_id' => '入库来源id',
     * 'remark' => '备注'
     * @return which the stock object
     *
     */
    public function out($arr)
    {
        $out = new OutModel;
        DB::beginTransaction();
        try {
            $out->create($arr);
            $obj = $this->where(['warehouse_positions_id'=>$arr['warehouse_positions_id']])->get()->first();
            if($obj) {
                $obj->all_amount -=$arr['amount'];
                $obj->available_amount -= $arr['amount'];
                if($obj->available_amount < 0)
                    throw new Exception('超出可用数量');
                $obj->total_amount -= $arr['total_amount'];
                $obj->save();
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();

        return $obj;
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
        $obj = $this->where('sku', $this->sku)->get()->toArray();
        $money = '';
        $amount = '';
        for($i=0; $i < count($obj); $i++)
        {
            $money += $obj[$i]['total_amount'];
            $amount += $obj[$i]['all_amount'];
        }

        return round($money/$amount, 3);
    }
}
