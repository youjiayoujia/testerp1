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
		foreach($response['data'] as $key=>$vo){
			$response['data'][$key]['purchase_items']=PurchaseItemModel::where('purchase_order_id',$vo->id)->get();
			$response['data'][$key]['purchase_post_num']=PurchasePostageModel::where('purchase_order_id',$vo->id)->count();
			$response['data'][$key]['purchase_post']=PurchasePostageModel::where('purchase_order_id',$vo->id)->first();
			foreach($response['data'][$key]['purchase_items'] as $v){
			$response['data'][$key]['sum_purchase_num'] +=$v->purchase_num;
			$response['data'][$key]['sum_arrival_num'] +=$v->arrival_num;
			$response['data'][$key]['sum_storage_qty'] +=$v->storage_qty;
			$response['data'][$key]['sum_purchase_account'] += ($v->purchase_num * $v->purchase_cost);
			$response['data'][$key]['sum_purchase_storage_account'] +=  ($v->storage_qty * $v->purchase_cost);
			}
			/*$response['data'][$key]['sum_purchase_num']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('purchase_num');
			$response['data'][$key]['sum_arrival_num']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('arrival_num');
			$response['data'][$key]['sum_storage_qty']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('storage_qty');*/
			}
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
			'purchaseItemsNum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('purchase_num'),
			'purchaseItemsArrivalNum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('arrival_num'),
			'storage_qty_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('storage_qty'),
			'postage'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage')
        ];
		$response['purchaseCost']=0;
		$response['storageCost']=0;
		foreach($response['purchaseItems'] as  $key=>$v){
			$response['purchaseCost'] +=$v->purchase_num * $v->purchase_cost;
			$response['storageCost'] +=$v->storage_qty * $v->purchase_cost;
			}
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
		if ($model->status ==4 ) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已完成.'));
        }
		if ($model->status ==5 ) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已取消.'));
        }
		$data=request()->all();
		if(isset($data['arr'])){
			if(isset($data['post'])){
				$post="";
				if($model->status <4 && $model->status >0){
				foreach($data['arr'] as $key=>$vo){
					foreach($data['post'] as $k=>$value){
						if($value['post_coding'] == $vo['post_coding']){
							$purchaseItem=PurchaseItemModel::find($vo['id']);
							
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
				}
			foreach($data['arr'] as $k=>$v){
				if($v['id']){
					$purchaseItem=PurchaseItemModel::find($v['id']);
					$itemPurchasePrice=$purchaseItem->item->purchase_price;
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
					if($item['purchase_num'] != $purchaseItem->purchase_num && $purchaseItem->examineStatus >0){
						if($purchaseItem->status < 4){
						$data['examineStatus']=2;
						}
					}
					$item['start_buying_time']=date('Y-m-d h:i:s',time());
					if($purchaseItem->status < 4){
					$purchaseItem->update($item);
					}
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
        return redirect( route('purchaseOrder.edit', $id));
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
	}
	
	/**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function cancelOrder($id)
	{	
		$num=purchaseItemModel::where('purchase_order_id',$id)->where('status','>',1)->count();
		if($num>0){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此采购单不能取消.'));
			}
		$purchaseItem=PurchaseItemModel::where('purchase_order_id',$id)->update(['status'=>5]);
		$this->model->update(['status'=>5,'examineStatus'=>3]);
		return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '改采购单已取消退回'));	
	}
	
	/**
     * ajax	新增物流单号物流费
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
	/**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
	
	public function addItem($id)
	{
		$response = [
            'metas' => $this->metas(__FUNCTION__),
			'purchase_order_id'=>$id,
        ];
		return view($this->viewPath.'addItem',$response);
	}
	/**
     * 创建采购条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
	public function createItem($id){
		$data=request()->all();
		$model=$this->model->find($id);
		$num=PurchaseItemModel::where('active_status','>',0)->where('sku',$data['sku'])->count();
		$Inum=ItemModel::where('sku',$data['sku'])->where('is_sale','<>',1)->count();
		$item=ItemModel::where('sku',$data['sku'])->where('is_sale',1)->first();
		if($num > 0 || $Inum > 0){
			return redirect(route('purchaseOrder.edit', $id))->with('alert', $this->alert('danger', $this->mainTitle . '此Item存在异常不能添加进此采购单.'));
		}
		if($model->close_status == 1){
			return redirect(route('purchaseOrder.edit', $id))->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已结算，不能新增Item.'));
			}
		$data['lack_num']=$data['purchase_num'];
		$data['warehouse_id']=$model->warehouse_id;
		$data['supplier_id']=$item->supplier_id;
		$data['purchase_order_id']=$id;
		PurchaseItemModel::create($data);
		if($model->examineStatus >0){
		$model->update(['examineStatus'=>2]);
		}
		return redirect( route('purchaseOrder.edit', $id));	
		}
	/**
	* 添加报等时间页面
	*
	* @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
	*/
	public function updateWaitTime($id){
		$response = [
				'metas' => $this->metas(__FUNCTION__),
				'purchase_item_id'=>$id,
			];
		return view($this->viewPath.'waitTime',$response);	
	}
	/**
	* 添加报等时间
	*
	* @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
	*/
	public function updateItemWaitTime($id){
		$data=request()->all();
		$purchaseItem=purchaseItemModel::find($id);
		purchaseItemModel::where('id',$id)->update(['wait_time'=>$data['wait_time'],'wait_remark'=>$data['wait_remark']]);
		return redirect( route('purchaseOrder.edit', $purchaseItem->purchase_order_id));	
		}
	/**
	* 打印
	*
	* @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
	*/
	public function printOrder($id){
		$model=$this->model->find($id);
		if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
			'purchase_num_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('purchase_num'),
			'storage_qty_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('storage_qty'),
			'postage_sum'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage'),
        ];
		$purchaseAccount='';
		foreach($response['purchaseItems'] as $key=>$v){
			$purchaseAccount +=$v->purchase_num * $v->purchase_cost;
			}
			$response['purchaseAccount']=$purchaseAccount;
        return view($this->viewPath . 'printOrder', $response);
		}

}









