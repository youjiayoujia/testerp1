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

class PurchaseAbnormalController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseItem )
    {
        $this->model = $purchaseItem;
        $this->mainIndex = route('purchaseAbnormal.index');
        $this->mainTitle = '采购需求';
		$this->viewPath = 'purchase.purchaseAbnormal.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('active','>',0)),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 批量创建异常采购条目页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{
		 $response = [
			'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'create', $response);		
	}
	
	/**
     * 批量创建异常采购条目
	 *     
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store()
	{
		$data=request()->all();
		$data['skus']=explode('#',$data['sku']);
		foreach($data['skus'] as $k=>$sku){
			$this->model->where('sku',$sku)->update(['active'=>$data['active'],'active_status'=>1]);
		}
		return redirect($this->mainIndex);
	}
	
	/**
     * 修改异常采购条目
	 *     
     * 
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
			'secondSupplier'=>SupplierModel::find($model->item->product->second_supplier_id),
        ];
        return view($this->viewPath . 'edit', $response);
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
        $data=request()->all();
		if($model->active==1 && $data['active_status']==0){
		if(empty($data['supplier_id'])){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '请选择更改后的供应商'));
			}else{
				ItemModel::where('sku',$model->sku)->update(['supplier_id'=>$data['supplier_id']]);
				}
		}
        $model->update($data);
        return redirect($this->mainIndex);
    }

	/**
     * ajax批量生成采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function addPurchaseOrder()
	{
		$purchaseIds=explode(',',request()->get('purchase_ids'));
		 if(!empty(request()->get('purchase_ids'))){
			$warehouse_supplier=$this->model->select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->whereIn('id',$purchaseIds)->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();
			if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$data['warehouse_id']=$v['warehouse_id'];		 
				$data['supplier_id']=$v['supplier_id'];
				$purchaseOrder=PurchaseOrderModel::create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
					$this->model->where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->whereIn('id',$purchaseIds)->update(['purchase_order_id'=>$purchaseOrderId]); 
				}				 
			}
			
		}
			 }else{ 
		$warehouse_supplier=$this->model->select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$data['warehouse_id']=$v['warehouse_id'];		 
				$data['supplier_id']=$v['supplier_id'];
				$purchaseOrder=PurchaseOrderModel::create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
					$this->model->where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->update(['purchase_order_id'=>$purchaseOrderId]); 
				}				 
			}
			
		}
		/*$warehouse_nosupplier=$this->model->select('id','warehouse_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id',0)->get()->toArray();
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$purchaseOrderModel =new PurchaseOrderModel;
				$data['warehouse_id']=$v['warehouse_id'];	
				$purchaseOrder=$purchaseOrderModel->create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
				$purchaseItem=$this->find($v['id']);
				$purchaseItem->purchase_order_id=$v['purchase_order_id'];
				$purchaseItem->save();
				}				 
			}
			return 1;*/
			}
		return 1;
		 		
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
			return redirect(route('purchaseOrder.index'));
		}else{
			return redirect( route('purchaseOrder.edit', $purchaseOrderId));
		}
	}
	

}









