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
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Warehouse\PositionModel;

class PurchaseListController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseList)
    {
        $this->model = $purchaseList;
        $this->mainIndex = route('purchaseList.index');
        $this->mainTitle = '采购对单';
		$this->viewPath = 'purchase.purchaseList.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('status','>',0)->where('active_status',0)->orderBy('status','desc')),
        ];
        return view($this->viewPath . 'index', $response);
    }

	/**
     * 批量对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function examinePurchaseItem()
	{
		$purcahse_active=explode(',',request()->get('purcahse_active'));
		foreach($purcahse_active as $key=>$value){
			$purcahse=explode('+',$value);
			$arrayItems=$this->model->find($purcahse[0]);
			if($arrayItems->item->weight >0){	
			if($purcahse[1]>0){
				$arrayItems->update(['active_status'=>1,'active'=>$purcahse[1]]);	
			}
			if($purcahse[1]==0 && $arrayItems->costExamineStatus ==2){
			$arrayItems->update(['status'=>2,'arrival_num'=>$arrayItems->purchase_num,'lack_num'=>0,'arrival_time'=>date('Y-m-d h:i:s',time())]);
			$this->generateBarCode($arrayItems->id);
			$num=$this->model->where('purchase_order_id',$arrayItems->purchase_order_id)->where('status','<',2)->count();
			$purchaseOrder=PurchaseOrderModel::find($arrayItems->purchase_order_id);
			if($num==0){
				$purchaseOrder->update(['status'=>3]);
			}
			}
		}
		}
		return 1;
		
	}
	 
	/**
     * 生成条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function generateBarCode($id){
			$model=$this->model->find($id);
			$stock_num=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->count();
			if($stock_num>0){
				$res=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->get();
				foreach($res as $key=>$v){
					$stockInIds[$key]=$v->id;
				}
				$randKey=array_rand($stockInIds,1);
				$stock=StockModel::find($stockInIds[$randKey]);	
				$model->update(['bar_code'=>$model->sku,'stock_id'=>$stock->id]);
			}else{
				$position=PositionModel::where('warehouse_id',$model->warehouse_id)->get();
				$position_num=PositionModel::where('warehouse_id',$model->warehouse_id)->count();
				if($position_num == 0){
					return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '仓库没有库位.'));
					}
					foreach($position as $key=>$v){
						$WarehousePositionIds[$key]=$v->id;
					}
				$randKey=array_rand($WarehousePositionIds,1);
				$stockData['warehouse_id']=$model->warehouse_id;
				$stockData['item_id']=$model->item->id;
				$stockData['warehouse_position_id']=$WarehousePositionIds[$randKey];
				$stockData['all_quantity']=$model->arrival_num;
				$stockData['hold_quantity']=$model->arrival_num;
				$stockData['amount']=$model->purchase_cost;
				$stockCreate=StockModel::create($stockData);
				$stockId=$stockCreate->id;
				$model->update(['bar_code'=>$model->sku,'stock_id'=>$stockId]);
		}
	}
	/**
     * 生成条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function printBarCode($id){
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'model' => $this->model->find($id),
        ];
		 return view($this->viewPath . 'printBarCode', $response);
		}
	/**
     * ajax回传修改产品重量
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function changeItemWeight(){
		$itemWeight=request()->get('item_weight');
		$purchase_id=request()->get('purchase_id');
		$model=$this->model->find($purchase_id);
		ItemModel::where('sku',$model->sku)->update(['weight'=>$itemWeight]);
		return 1;
		}
	
	/**
     * ajax回传修改采购条目物流单号
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function changePurchaseItemPostcoding(){
		$post_coding=request()->get('post_coding');
		$purchase_id=request()->get('purchase_id');
		$model=$this->model->find($purchase_id);
		$model->update(['post_coding'=>$post_coding]);
		return 1;
		}	

}









