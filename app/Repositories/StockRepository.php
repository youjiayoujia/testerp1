<?php
/**
 * 库存操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/30
 * Time:14:48
 *
 */
namespace App\Repositories;

use DB;
use App\Base\BaseRepository;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;

class StockRepository extends BaseRepository
{
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
    
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
    }

    /**
     * get the unit_cost by the position 
     *
     *  @return unit_price
     *
     */
    public function getUnitCost($sku)
    {
        $stock = $this->model->where('sku', $sku)->first();
    
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
        return $this->model->where($arr)->get($field);
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
        $stock = new StockModel;
        DB::beginTransaction();
        try {
            $in->create($arr);
            $obj = $stock->where(['warehouse_positions_id'=>$arr['warehouse_positions_id']])->get()->first();
            if($obj) {
                $obj->all_amount += $arr['amount'];
                $obj->available_amount +=$arr['amount'];
                $obj->total_amount +=$arr['total_amount'];
                $obj->save();
            } else {
                $tmp = $stock->create($arr);
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
        $stock = new StockModel;
        DB::beginTransaction();
        try {
            $out->create($arr);
            $obj = $stock->where(['warehouse_positions_id'=>$arr['warehouse_positions_id']])->get()->first();
            if($obj) {
                $obj->all_amount -=$arr['amount'];
                $obj->available_amount -= $arr['amount'];
                $obj->total_amount -= $arr['total_amount'];
                $obj->save();
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();

        return $obj;
    }
}