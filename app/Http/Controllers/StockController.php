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
            'positions' => PositionModel::where(['warehouses_id' => $model->warehouses_id])->get(['id', 'name']),
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     * 获取对象，通过仓库和库位
     * 某仓库某库位的对象
     *
     *
     *
     *
     *
     */
    public function ajaxGetByPosition()
    {
        if(request()->ajax()) {
            $warehouses_id = $_GET['warehouses_id'];
            $warehouse_positions_id = $_GET['warehouse_positions_id'];
            $obj = StockModel::where(['warehouses_id'=>$warehouses_id, 'warehouse_positions_id'=>$warehouse_positions_id])->get();
            echo json_encode($obj);
        } else {
            return false;
        }
    }
    public function ajaxGetMessage()
    {
        if(request()->ajax()) {
            $sku = $_GET['sku'];
            $warehouses_id = $_GET['warehouses_id'];
            $obj = ItemModel::where(['sku'=>$sku])->get()->first();
            $obj1 = StockModel::where(['warehouses_id'=>$warehouses_id, 'sku'=>$sku])->get();
            if(!$obj) {
                echo json_encode('sku_none');
                exit;
            }
            if(!count($obj1)) {
                echo json_encode('stock_none');
                exit;
            }
            $arr[] = $obj;
            $arr[] = $obj1;
            foreach($obj1 as $tmp) {
                $buf = PositionModel::where(['id'=>$tmp->warehouse_positions_id])->get()->first();
                $arr[2][] = $buf;
            }
            if($obj1)
                $arr[3] = $obj1->first()->unit_cost;
            echo json_encode($arr);
        } else {
            echo json_encode('false');
        }
    }

    /**
     *  获取某商品的平均单价
     *
     * @param none
     * @return json
     *
     */
    public function getUnitCost()
    {  
        $sku = $_GET['sku'];
        $unit = $this->model->where(['sku'=>$sku])->get()->first()->unit_cost;

        if($unit) {
            echo json_encode($unit);
        } else {
            echo json_encode('none');
        }
    }

    /**
     * 获取product 可用数量 
     *
     * @param none
     * @return json|可用数量
     *
     */
    public function getAvailableAmount()
    {
        $position = $_GET['position'];
        $obj = $this->model->getObj(['warehouse_positions_id'=>$position])->first();
        $cost = $obj->unit_cost;
        if($obj)
            echo json_encode([$obj->available_amount,$cost]);
        else
            echo json_encode('none');
    }

    /**
     * 根据仓库获取position,根据第一个Position获取item_id,sku,available_amount 
     *
     * @param none
     * @return json
     *
     */
    public function getpsi()
    {
        $position = new PositionModel;
        $warehouse = $_GET['warehouse'];
        $arr[] = $position->getObj(['warehouses_id'=>$warehouse], ['id', 'name'])->toArray();
        if(!empty($arr[0])) {
            $obj = $this->model->getObj(['warehouse_positions_id'=>$arr[0][0]['id']], ['item_id', 'sku', 'available_amount'])->first();
            if($obj) {
                $arr[1][] = $obj ->toArray();
            }
            echo json_encode($arr);
        } else {
            echo json_encode('none');
        }
    }

    /**
     * 根据仓库库位定sku 
     *
     * @param none
     * @return json
     *
     */
    public function stockposition()
    {
        $warehouse = $_GET['warehouse'];
        $position = $_GET['position'];

        $buf = $this->model->getObj(['warehouses_id'=>$warehouse, 'warehouse_positions_id'=>$position], ['sku'])->first();

        if($buf->toArray())
            echo json_encode($buf);
        else
            echo json_encode('none');
    }
}