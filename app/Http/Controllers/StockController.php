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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
            'warehouses' => WarehouseModel::all(),
        ];

        return view($this->viewPath.'edit', $response);
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
        $unit = $this->model->getunitcost($sku);

        if($unit) {
            echo json_encode($unit);
        } else {
            echo json_encode('1');
        }
    }

    /**
     * 获取sku
     * 
     * @param none
     * @return 'none' or json
     *
     */
    public function getSku()
    {
        $val_position = $_GET['val_position'];
        $obj = $this->model->getObj(['warehouse_positions_id'=>$val_position])->first();
        $cost = $obj->unit_cost;
        if($obj)
            echo json_encode([$obj->sku, $obj->available_amount, $obj->item_id, $cost]);
        else
            echo json_encode('none');
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