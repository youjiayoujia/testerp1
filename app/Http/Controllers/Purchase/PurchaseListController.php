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
            'data' => $this->autoList($this->model->where('status','>','0')->orderBy('status')),
        ];
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
		if($data['arrival_num']==$model->purchase_num){
			$data['lack_num']=0;
			$data['status']=2;
		}
		if($data['active']>0){
			$data['active_status']=1;
		}
		$model->update($data);
        return redirect($this->mainIndex);		
	}
	
	public function updateActive($id)
	{
		$data=request()->all();
		$this->model->activeUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	 
	
	public function activeChange($id)
	{
		$res=$this->model->find($id);
		$second_supplier_id=$res->item->second_supplier_id;
		$second_supplier=SupplierModel::find($second_supplier_id);	
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'abnormal' => $res,
			'second_supplier'=>$second_supplier,
        ];
        return view($this->viewPath . 'changeActive', $response);
			
	}
	/**
     * 批量对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function examinePurchaseItem()
	{
		$purchaseItemIds=explode(',',request()->get('purchase_ids'));
		$arrayItems=$this->model->find($purchaseItemIds);
		foreach($arrayItems as $vo)
		{
			$vo->update(['status'=>2,'arrival_num'=>$vo->purchase_num,'lack_num'=>0]);
			$num=$this->model->where('purchase_order_id',$vo->purchase_order_id)->where('status','<>',2)->count();
			$purchaseOrder=PurchaseOrderModel::find($vo->purchase_order_id);
			if($num==0){
				$purchaseOrder->update(['status'=>3]);
			}
		}
		return 1;
		
	}
	/**
     * 对单入库
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function stockIn($id){
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'model' => $this->model->find($id),
        ];
		 return view($this->viewPath . 'stockIn', $response);
	}
	/**
     * 生成条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	/*public function generateDarCode($id){
		$model=$this->model->find($id);
		$res=InModel::where('relation_id',$id)->count();			
		if($res>0){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '已生成条码.'));		
		}else{
			$stock_num=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->count();
			if($stock_num>0){
				$res=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->get();
				foreach($res as $key=>$v){
					$WarehousePositionIds[$key]=$v->warehouse_position_id;
				}
					
			}else{
				$position=PositionModel::where('warehouse_id',$model->warehouse_id)->get();
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
				ItemModel::find($model->item->id)->in($stockData['warehouse_position_id'],$model->arrival_num, $model->purchase_cost*$model->purchase_num, 0,$model->id, $remark = '订单采购！');
				$position=PositionModel::find($WarehousePositionIds[$randKey]);
				$warehouseId=$position->warehouse_id;
				$warehouseName=$position->name;
				$sku=$model->sku;
				$barCode=$sku.$warehouseId.$warehouseName;
				echo $barCode;
			}
		}
	}*/
	
}









