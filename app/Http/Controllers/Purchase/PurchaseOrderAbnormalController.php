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

class PurchaseOrderAbnormalController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder)
    {
        $this->model = $purchaseOrder;
        $this->mainIndex = route('purchaseOrderAbnormal.index');
        $this->mainTitle = '异常采购单';
		$this->viewPath = 'purchase.purchaseOrderAbnormal.';
    }
    
	
	public function index()
    {
		$purchaseAbnormalOrder=PurchaseItemModel::select('purchase_order_id')->where('active','>',0)->where('purchase_order_id','>','0')->groupBy('purchase_order_id')->get()->toArray();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->whereIn('id',$purchaseAbnormalOrder)),
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
		if ($model->examineStatus !=2) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
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
     * 更新采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$model=$this->model->find($id);
		if ($model->examineStatus !=2) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
        }
		$data=request()->all();		
		if(isset($data['arr'])){
			foreach($data['arr'] as $k=>$v){
				if($v['id']){
					$purchaseItem=PurchaseItemModel::find($v['id']);
					$itemPurchasePrice=$purchaseItem->item->product->purchase_price;
					$purchase_num=$purchaseItem->purchase_num;
					foreach($v as $key=>$vo){
						$item[$key]=$vo;	
					}
					if($v['active']>0){
						$item['active_status']=1;
					}
					if($item['purchase_cost'] >0.6*$itemPurchasePrice && $item['purchase_cost'] <1.3*$itemPurchasePrice ){
						$item['costExamineStatus']=2;
					}else{
						$item['costExamineStatus']=0;	
					}
					if($item['status']>0){
						$data['status']=1;
					}
					$purchaseItem->update($item);
					$data['total_purchase_cost'] +=$v['purchase_cost']*$purchase_num;
					unset($item);
				}
			}
		}
		$num=PurchaseItemModel::where('purchase_order_id',$id)->where('costExamineStatus','<>',2)->count();
		if($num ==0){
			$data['costExamineStatus']=2;
			}	
		$model->update($data);
        return redirect( route('purchaseOrder.edit', $id));		
	}
	
	/**
     * 审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function changeExamineStatus($id,$examineStatus)
	{
		$model=$this->model->find($id);
		if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
		$data['examineStatus']=$examineStatus;
		$model->update($data);
		if($examineStatus==1){
			$this->model->cancelOrderItems($id);
		}
		return redirect($this->mainIndex);
	}
	
	/**
     * 导出采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function excelOut($id)
	{
		$this->model->purchaseOrderExcelOut($id);	
	}
	
	/**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function cancelOrder($id)
	{
		$this->model->cancelOrderItems($id);
		return redirect($this->mainIndex);	
	}
	
	/**
     * 批量审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function examinePurchaseOrder(){
		$purchaseOrderIds=request()->get('purchase_ids');
		$arrayIds=explode(',',$purchaseOrderIds);
		$purchaseOrders=$this->model->find($arrayIds);
			foreach($purchaseOrders as $vo){
				$vo->update(['examineStatus'=>2]);
				}
		return 1;
		}
		
}









