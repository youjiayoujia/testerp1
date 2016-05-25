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
use App\Models\UserModel;
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
            'users' => UserModel::all(),
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
        $data=request()->all();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->supplierCreate($data, request()->file('qualifications'));
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
            'users' => UserModel::all(),
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
        $data=request()->all();
        $this->validate(request(), $this->model->rules('update', $id));
        if($model->purchase_id != request('purchase_id')) {
            SupplierChangeHistoryModel::create([              
                'supplier_id' => $id,
                'from' => $model->purchase_id,
                'to' =>request()->input('purchase_id'),
                'adjust_by' => '3',
            ]);
        }
        $this->model->updateSupplier($id,$data,request()->file('qualifications'));
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
	
	public function beExamine(){
		$channel_id = request()->input('channel_id');
        $product_id_str = request()->input('product_ids');
        $product_id_arr = explode(',',$product_id_str);
		$suppliers=$this->model->find($product_id_arr);
		foreach($suppliers as $key=>$vo){
			if($vo->examine_status <2){
				$vo->update(['examine_status'=>$channel_id]);
			}
			}
		return 1;	
	}
	
}