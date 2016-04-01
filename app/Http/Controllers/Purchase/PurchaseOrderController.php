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
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;

class PurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder)
    {
        $this->model = $purchaseOrder;
        $this->mainIndex = route('purchaseOrder.index');
        $this->mainTitle = '采购单';
		$this->viewPath = 'purchase.purchaseOrder.';
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
     * 采购页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
        ];
        return view($this->viewPath . 'edit', $response);	
	}
	 
	  /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
        ];
        return view($this->viewPath . 'show', $response);
    }
	
	/**
     * 创建采购条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function store()
	{	
		$data=request()->all();
		$this->model->addPurchaseOrder($data);
        return redirect($this->mainIndex);	
	}
	
	
	
	
	/**
     * 更新采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$this->model->purchaseItemUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	/**
     * 审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function examinePurchaseOrder()
	{
		$purchaseOrderIds=explode(',',request()->get('purchase_ids'));
		$this->model->updatePurchaseOrderExamine($purchaseOrderIds);
		return 1;
	}
	
	
	
	
		
	public function printOrder($id)
	{		
		echo 111;exit;
	}
	
	public function excelOut($id)
	{
		$this->model->purchaseOrderExcelOut($id);	
	}
	public function cancelOrder($id){
		$this->model->cancelOrderItems($id);
		 return redirect($this->mainIndex);	
		}
}









