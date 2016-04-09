<?php
/**
 *  供货商控制器
 *  处理与供货商相关的操作
 *
 * @author:MC<178069409@qq.com>
 *    Date:2015/12/18
 *    Time:11:18
 *
 */

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Product\SupplierModel;
use App\Models\product\SupplierLevelModel;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
        $this->mainIndex = route('productSupplier.index');
        $this->mainTitle = '供货商';
        $this->viewPath = 'product.supplier.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'levels' => SupplierLevelModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
            'levels' => SupplierLevelModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function createLevel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'createLevel', $response);
    }

    public function levelStore()
    {
        SupplierLevelModel::create(request()->all());

        return redirect($this->mainIndex);
    }
}