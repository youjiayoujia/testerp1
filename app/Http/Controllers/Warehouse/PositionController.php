<?php
/**
 * 库位控制器
 * 处理库位相关的Request与Response
 *
 * @author: MC
 * Date: 15/12/18
 * Time: 16:15pm
 */

namespace App\Http\Controllers\warehouse;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\PositionModel;
use App\Models\WarehouseModel;

class PositionController extends Controller
{
    protected $warehouse;

    public function __construct(PositionModel $position, WarehouseModel $warehouse)
    {
        $this->model = $position;
        $this->warehouse = $warehouse;
        $this->mainIndex = route('warehousePosition.index');
        $this->mainTitle = '库位';
        $this->viewPath = 'warehouse.position.';
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return none
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => $this->warehouse->all(),
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
            'warehouses' => $this->warehouse->all(),
            'position' => $this->model->find($id),
        ];

        return view($this->viewPath.'edit', $response);
    }
}