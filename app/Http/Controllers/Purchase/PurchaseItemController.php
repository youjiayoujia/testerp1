<?php
/**
 * 采购条目控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Product\SupplierModel;

class PurchaseItemController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseItem )
    {
        $this->model = $purchaseItem;
        $this->mainIndex = route('purchaseItem.index');
        $this->mainTitle = '采购需求';
		$this->viewPath = 'purchase.purchaseItem.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 创建采购条目页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{
		 $response = [
			'metas' => $this->metas(__FUNCTION__),
			'warehouse' => WarehouseModel::all(),
        ];
        return view($this->viewPath . 'create', $response);		
	}
	
	/**
     * 创建采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store()
	{
		$data=request()->all();
		$productItem=ItemModel::where('sku',$data['sku'])->first();
		 if (!$productItem) {
            return redirect(route('purchaseItem.create'))->with('alert', $this->alert('danger','此Item不存在!'));
        }
		$data['supplier_id']=$productItem->supplier_id;
		$data['lack_num']=$data['purchase_num'];
		$this->model->create($data);
		return redirect($this->mainIndex);
	}
	/**
     * 更新采购条目
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
		if ($model->status >1) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '已对单不能修改数量.'));
        }
        $data=request()->all();
        $this->validate(request(), $this->model->rules('update', $id));
		$data['lack_num']=$data['purchase_num']-$model->arrival_num;
        $model->update($data);
        return redirect($this->mainIndex);
    }

	
	/**
     * 审核采购价格
     *
     * @param $id采购条目id
	 * @param $costExamineStatus价格审核状态
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function costExamineStatus($id,$costExamineStatus){
		$model=$this->model->find($id);
		$data['costExamineStatus']=$costExamineStatus;
		$model->update($data);
		if($costExamineStatus==2){
			ItemModel::where('sku',$model->sku)->update(['purchase_price'=>$model->purchase_cost]);
			}
		$num=$this->model->where('purchase_order_id',$model->purchase_order_id)->where('costExamineStatus','<>',2)->count();
		if($num==0){
			PurchaseOrderModel::where('id',$model->purchase_order_id)->update(['costExamineStatus'=>2]);
		}
		return redirect( route('purchaseOrder.edit', $model->purchase_order_id));	
	}	
		

	/**
     * 去除采购单中异常条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function cancelThisItem($id)
	{
		$model=$this->model->find($id);
		$purchaseOrderId=$model->purchase_order_id;
		$data['purchase_order_id']=0;
		$model->update($data);
		$num=$this->model->where('purchase_order_id',$purchaseOrderId)->count();
		if($num==0)
		{
			PurchaseOrderModel::destroy($purchaseOrderId);	
			return redirect(route('purchaseOrderAbnormal.index'));
		}else{
			return redirect( route('purchaseOrderAbnormal.edit', $purchaseOrderId));
		}
	}
	

}









