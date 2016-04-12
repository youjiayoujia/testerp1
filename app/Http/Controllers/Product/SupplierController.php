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
use App\Models\Product\SupplierLevelModel;
use App\Models\Product\SupplierChangeHistoryModel;

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
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->create(request()->all());
        SupplierChangeHistoryModel::create([              
            'supplier_id' => $model->id,
            'to' =>request()->input('purchase_id'),
            'adjust_by' => '3',
        ]);
        return redirect($this->mainIndex);
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

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        if($model->purchase_id != request('purchase_id')) {
            SupplierChangeHistoryModel::create([              
                'supplier_id' => $id,
                'from' => $model->purchase_id,
                'to' =>request()->input('purchase_id'),
                'adjust_by' => '3',
            ]);
        }
        $model->update(request()->all());
        return redirect($this->mainIndex);
    }

    /**
     * 跳转创建供货商等级 
     *
     * @param none
     * @return view
     *
     */
    public function createLevel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'createLevel', $response);
    }

    /**
     * 等级save 
     *
     * @param none
     * @return view
     *
     */
    public function levelStore()
    {
        SupplierLevelModel::create(request()->all());

        return redirect($this->mainIndex);
    }
}