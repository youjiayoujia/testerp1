<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;

class StockController extends Controller
{
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
        $this->mainIndex = route('stock.index');
        $this->mainTitle = '库存';
        $this->viewPath = 'stock.';
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::all(),
            'items' => ItemModel::all(), 
        ];

        return view($this->viewPath.'create', $response);
    }

    /**
     * 跳转数据编辑页
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'warehouses' => WarehouseModel::all(),
            'positions' => PositionModel::where('warehouses_id', $model->warehouses_id)->get(),
            'items' => ItemModel::all(),
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     * 获取库存对象，通过仓库和库位
     * 某仓库某库位的对象里面的所有sku
     *
     * @return obj
     * @var array
     *
     */
    public function ajaxGetByPosition()
    {
            $position = request()->input('position');
            $obj = StockModel::where(['warehouse_positions_id'=>$position])->with('items')->get();

            return json_encode($obj);
        
        return json_encode('false');
    }

    /**
     * 获取信息 
     * 传参：sku，仓库号
     * array[0] => item号的相应对象
     * array[1] => 通过仓库和sku 来获取对应的库存对象
     * array[2] => 对应于array[1]的position对象
     * array[3] => 获取商品单价
     *
     * @return array
     *
     */
    public function ajaxGetMessage()
    {
        if(request()->ajax()) {
            $sku = request()->input('sku');
            $warehouses_id = request()->input('warehouses_id');
            $obj = ItemModel::where(['sku'=>$sku])->get();
            if(!count($obj)) {
                return json_encode('sku_none');
            }
            $obj = $obj->first();
            $obj1 = StockModel::where(['warehouses_id'=>$warehouses_id, 'items_id'=>$obj->id])->get();
            if(!count($obj1)) {
                return json_encode('stock_none');
            }
            $arr[] = $obj;
            $arr[] = $obj1;
            foreach($obj1 as $tmp) {
                $buf = $tmp->position->first();
                $arr[2][] = $buf;
            }
            if($obj1)
                $arr[3] = $obj1->first()->unit_cost;
            return json_encode($arr);
        }

        return json_encode('false');
    }

    /**
     * 调拨调出仓库对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotOutWarehouse()
    {
        if(request()->ajax()) {
            $warehouse = request()->input('warehouse');
            $buf = $this->model->where('warehouses_id', $warehouse)->distinct()->with('items')->get()->toArray();
            if(!count($buf)) {
                return json_encode('none');
            }
            $arr[] = $buf;
            $arr[] = $this->model->where('warehouses_id', $warehouse)->get()->first()->toArray();
            $obj = $this->model->where(['warehouses_id'=>$warehouse, 'items_id'=>$arr[0][0]['items']['id']])->get();
            foreach($obj as $val)
            {
                $tmp = $val->position ? $val->position->toArray() : '';
                $arr[2][] = $tmp;
            }
            return json_encode($arr);
        }

        return json_encode('false');
    }

    /**
     * 调拨库位对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotPosition()
    {
        if(request()->ajax()) {
            $position = request()->input('position');
            $items_id = $_GET['items_id'];
            $obj = StockModel::where(['warehouse_positions_id'=>$position, 'items_id'=>$items_id])->get()->first();
            $arr[] = $obj->toArray();
            $arr[] = $obj->unit_cost;
            if($arr) {
                return json_encode($arr);
            } else {
                return json_encode('none');
            }
        }

        return json_encode('false');
    }

    /**
     * 调拨sku对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotSku()
    {
        
            $warehouse = request()->input('warehouse');
            $items_id = request()->input('items_id');
            $obj = StockModel::where(['warehouses_id'=>$warehouse, 'items_id'=>$items_id])->get()->first();
            if(!$obj) {
                return json_encode('none');
            }
            $arr[] = $obj->toArray();
            $tmp = StockModel::where(['warehouses_id'=>$warehouse, 'items_id'=>$items_id])->distinct()->get();
            if(!$tmp) {
                return json_eoncode('none');
            }
            foreach($tmp as $val) 
            {
                $buf = $val->position->toArray();
                $arr[1][] = $buf;
            }
            $arr[2] = $obj->unit_cost;
            if($arr) {
                return json_encode($arr);
            } else {
                return json_encode('none');
            }
        

        return json_encode('false');
    }
}