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
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Warehouse\PositionModel;

class PurchaseItemListController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseList)
    {
        $this->model = $purchaseList;
        $this->mainIndex = route('purchaseItemList.index');
        $this->mainTitle = '采购条目';
		$this->viewPath = 'purchase.purchaseItemList.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
		foreach($response['data'] as $key=>$vo){
			$response['data'][$key]['all_quantity']=StockModel::where('item_id',$vo->item->id)->sum('all_quantity');
			}
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 对单界面
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
        ];
        return view($this->viewPath . 'edit', $response);
    }
	
	/**
     * 对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$model=$this->model->find($id);
		$data['active_status']=1;
		$model->update($data);
        return redirect($this->mainIndex);		
	}
	/**
     * 批量还原采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseItemReduction(){
		$response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
		
        return view($this->viewPath . 'itemReduction', $response);
		}
		
	/**
     * 批量还原采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function reductionUpdate(){
		$data=request()->all();
		$updateIds=explode('#',$data['purchaseItemIds']);
		$items=$this->model->find($updateIds);
		foreach($items as $key=>$item){
			if($data['status'] == 0){
				if($item->status == 1){
					$item->update(['status'=>0]);
				}
				$orderItemNum=$this->model->where('purchase_order_id',$item->purchase_order_id)->where('status','>',0)->count();
				if($orderItemNum ==0){
					PurchaseOrderModel::where('id',$item->purchase_order_id)->update(['status'=>0]);	
				}
			}elseif($data['status'] == 1){
				if($item->status == 0){
					$item->update(['status'=>1]);
				}			
				PurchaseOrderModel::where('id',$item->purchase_order_id)->where('examineStatus',2)->update(['status'=>1]);	
			}
		}
		return redirect($this->mainIndex);
	}
	
	/**
     * 单个还原采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function itemReductionUpdate($id){	 
		$item=$this->model->find($id);
			if($item->status == 1){
				$item->update(['status'=>0]);
			}
			$orderItemNum=$this->model->where('purchase_order_id',$item->purchase_order_id)->where('status','>',0)->count();
			if($orderItemNum ==0){
				PurchaseOrderModel::where('id',$item->purchase_order_id)->update(['status'=>0]);	
				}
		return redirect($this->mainIndex);
	}
	
}









