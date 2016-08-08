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
use App\Models\Logistics\CollectionInfoModel;
use App\Models\Logistics\SupplierModel;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
        $this->mainIndex = route('logisticsSupplier.index');
        $this->mainTitle = '物流商';
        $this->viewPath = 'logistics.supplier.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'collectionInfos' => CollectionInfoModel::all(),
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
            'collectionInfos' => CollectionInfoModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

}