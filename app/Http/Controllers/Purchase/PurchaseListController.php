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
use App\Models\Warehouse\PositionModel;

class PurchaseListController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseList,StockModel $stock,PositionModel $position)
    {
        $this->model = $purchaseList;
		$this->stock=$stock;
		$this->position=$position;
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
		$model=request()->all();
		$model->uopdate($data);
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
		$second_supplier_id=$res->purchaseItem->second_supplier_id;
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
	
	public function stockIn($id){
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'model' => $this->model->find($id),
        ];
		 return view($this->viewPath . 'stockIn', $response);
	}
	
	public function generateDarCode($id){
		$purchaseItem=$this->model->find($id);
		$res=$this->stock->where('item_id',$purchaseItem->purchaseItem->id)->where('warehouse_id',$purchaseItem->warehouse_id)->count();
		$itemStock=$this->stock->where('item_id',$purchaseItem->purchaseItem->id)->where('warehouse_id',$purchaseItem->warehouse_id)->first();		
		/*if($res>0){
			$stockData['all_quantity']=$itemStock->all_quantity+$purchaseItem->arrival_num;
			$stockData['hold_quantity']=$itemStock->hold_quantity+$purchaseItem->arrival_num;
			
		}else{*/
			$position=$this->position->where('warehouse_id',$purchaseItem->warehouse_id)->get();
			foreach($position as $key=>$v){
				$WarehousePositionIds[$key]=$v->id;
				}
			$randKey=array_rand($WarehousePositionIds,1);
			$stockData['warehouse_id']=$purchaseItem->warehouse_id;
			$stockData['item_id']=$purchaseItem->purchaseItem->id;
			$stockData['warehouse_position_id']=$WarehousePositionIds[$randKey];
			$stockData['all_quantity']=$purchaseItem->arrival_num;
			$stockData['hold_quantity']=$purchaseItem->arrival_num;
			$stockData['amount']=$purchaseItem->purchase_cost;
			$stockCreate=$this->stock->create($stockData);
			$stockId=$stockCreate->id;
			$item= new ItemModel;
			$item->find($purchaseItem->purchaseItem->id)->in($stockData['warehouse_position_id'],$purchaseItem->arrival_num, $purchaseItem->purchase_cost, 0,$purchaseItem->purchase_order_id, $remark = '订单采购！');
			$position=$this->position->find($WarehousePositionIds[$randKey]);
			$warehouseId=$position->warehouse_id;
			$warehouseName=$position->name;
			$sku=$purchaseItem->sku_id;
			$barCode=$sku.$warehouseId.$warehouseName;
			//$d = new DNS1D();
			//$d->setStorPath(__DIR__."/cache/");
			//echo $d->getBarcodeHTML("9780691147727", "EAN13");
			//echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T");
			//echo $barCode;exit;
		/*}*/
			//$barcode=$this->stock->where(''=>'',''=>'')->get();
	}
	
}









