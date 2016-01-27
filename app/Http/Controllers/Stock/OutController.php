<?php
/**
 * 出库控制器
 * 处理出库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/24
 * Time: 11:05am
 */

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\OutModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;

class OutController extends Controller
{
    public function __construct(OutModel $out)
    {
        $this->model = $out;
        $this->mainIndex = route('stockOut.index');
        $this->mainTitle = '出库';
        $this->viewPath = 'stock.out.';
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
            'data' => config('out'),
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
            'data' => config('out'),
            'out' => $this->model->find($id),
            'warehouses' => WarehouseModel::all(),
        ];

        return view($this->viewPath.'edit', $response);
    }
}