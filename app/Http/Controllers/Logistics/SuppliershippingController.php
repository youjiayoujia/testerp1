<?php
/**
 * 物流商控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:42
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\SuppliershippingModel as SuppliershippingModel;
use App\Models\Logistics\SupplierModel as SupplierModel;

class SuppliershippingController extends Controller
{
    public function __construct(SuppliershippingModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('supplierShipping.index');
        $this->mainTitle = '物流商物流方式';
        $this->viewPath = 'logistics.suppliershipping.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'suppliers'=>$this->getSuppliers(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'suppliers'=>$this->getSuppliers(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function getSuppliers()
    {
        return SupplierModel::all();
    }
}