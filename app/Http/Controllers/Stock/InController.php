<?php
/**
 * 入库控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\InModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;

class InController extends Controller
{
    public function __construct(InModel $in)
    {
        $this->model = $in;
        $this->mainIndex = route('stockIn.index');
        $this->mainTitle = '入库';
        $this->viewPath = 'stock.in.';
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
            'data' => config('in.in'),
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
            'data' => config('in'),
            'in' => $this->model->get($id),
            'warehouses' => WarehouseModel::all(),
        ];

        return view($this->viewPath.'edit', $response);
    }
    
    /**
     * 获取itemid，返回
     *
     * @param none 
     * @return json
     *
     */
    public function getItemId()
    { 
        $sku_val = $_GET['sku_val']; 
        $id = $this->in->getitemid($sku_val);

        echo json_encode($id);
    }

    /**
     * 获取库位信息,return 
     *
     * @param none
     * @return json
     *
     */
    public function getPosition()
    {
        $warehouses_id = $_GET['val'];
        $arr = $this->in->getPosition($warehouses_id);

        echo json_encode($arr);
    }
}