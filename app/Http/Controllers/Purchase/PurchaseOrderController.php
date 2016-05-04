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
use App\Models\ItemModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchasePostageModel;

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
		if ($model->examineStatus !=2) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
			'purchasePostage'=>PurchasePostageModel::where('purchase_order_id',$id)->get(),
			'purchaseSumPostage'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage'),
			'current'=>count(PurchasePostageModel::where('purchase_order_id',$id)->get()->toArray()),
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
		if($model->examineStatus !=2){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
        }
		$data=request()->all();	
		if(isset($data['arr'])){
			if(isset($data['post'])){
				$post="";
				foreach($data['arr'] as $key=>$vo){
					foreach($data['post'] as $k=>$value){
						if($value['post_coding'] == $vo['post_coding']){
							$purchaseItem=PurchaseItemModel::find($vo['id']);
							$post[$key]['purchase_item_id']=$vo['id'];
							$post[$key]['purchase_order_id']=$purchaseItem->purchase_order_id;
							$post[$key]['post_coding']=$value['post_coding'];
							$post[$key]['postage']=$value['postage'];
							}
						}
					}
					if(!empty($post)){
						foreach($post as $num=>$val){
							$num=PurchasePostageModel::where('post_coding',$val['post_coding'])->count();
							if($num==0){
								PurchasePostageModel::create($val);
							}else{
								PurchasePostageModel::where('post_coding',$val['post_coding'])->update(['postage'=>$val['postage']]);
								}
							
							}	
						}
				}
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
						ItemModel::where('sku',$purchaseItem->sku)->update(['purchase_price'=>$item['purchase_cost']]);	
					}else{
						$item['costExamineStatus']=0;	
					}
					if($item['status']>0){
						$data['status']=1;
					}
					if($item['purchase_num']>0){
						$data['status']=$item['purchase_num'];
						}
					$item['start_buying_time']=date('Y-m-d h:i:s',time());
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
		$data['start_buying_time']=date('Y-m-d h:i:s',time());	
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
			$this->cancelOrder($id);
		}
		return redirect($this->mainIndex);
	}
	
	
	
	/**
     * 导出采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseOrdersOut()
	{
		 $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
		return view($this->viewPath.'excelOut',$response);	
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
	/**
     * 导出3天未到货采购单
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function excelOrderOut($num){
		if($num==0){
			$this->model->allPurchaseExcelOut();	
		}elseif($num==3){
			$this->model->noArrivalOut();
			}
		/*if($num==0){
			$purchaseOrderIds=PurchaseItemModel::select('purchase_order_id')->where('status','>',0)->distinct('purchase_order_id')->get();
		}else{
			$purchaseOrderIds=PurchaseItemModel::select('purchase_order_id')->where('status','>',0)->where('start_buying_time','<',date('Y-m-d',(time()-3600*24*$num)))->distinct('purchase_order_id')->get();
		}
		$this->model->excelOrdersOut($purchaseOrderIds);*/
			
	}
	
	/**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function cancelOrder($id)
	{	
		$num=purchaseItemModel::where('purchase_order_id',$id)->where('status','>',2)->count();
		if($num>0){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此采购单不能取消.'));
			}
		$purchaseItem=PurchaseItemModel::where('purchase_order_id',$id)->update(['active'=>0,'active_status'=>0,'remark'=>'','arrival_time'=>'','purchase_order_id'=>0]);
		$this->model->destroy($id);
		return redirect($this->mainIndex);	
	}
	
	/**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxPostAdd()
    {
        if (request()->input('current')) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath . 'add', $response);
        }
        return null;
    }
}









