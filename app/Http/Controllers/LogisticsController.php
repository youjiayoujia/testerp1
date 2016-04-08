<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use App\Models\Logistics\CodeModel;
use App\Models\LogisticsModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\SupplierModel;

class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $logisticsModel)
    {
        $this->model = $logisticsModel;
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
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
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
        $logistic = $this->model->find($id);
        if (!$logistic) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $logistic,
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     *ajax获取zone
     */
    public function zone()
    {
        $id = request()->input("id");
        $buf =$this->model->find($id)->species;
        return json_encode($buf);
    }

    /**
     * 获取号码池数量
     */
    public function poolQuantity()
    {

    }

}