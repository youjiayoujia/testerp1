<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use App\Models\LogisticsModel;
use App\Models\WarehouseModel as Warehouse;
use App\Models\Logistics\SupplierModel as Supplier;


class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流';
        $this->viewPath = 'logistics.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses'=>Warehouse::all(),
            'suppliers'=>Supplier::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
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
            'warehouses'=>Warehouse::all(),
            'suppliers'=>Supplier::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     *
     */
    public function zone()
    {
        $id = $_GET['id'];
        $buf =$this->model->find($id)->species;
        echo json_encode($buf);
    }

}