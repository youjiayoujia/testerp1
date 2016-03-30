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
use App\Models\Purchase\PurchaseListModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Warehouse\PositionModel;

class PurchaseListController extends Controller
{

    public function __construct(PurchaseListModel $purchaseList,WarehouseModel $warehouse,SupplierModel $supplier,StockModel $stock,PositionModel $position)
    {
        $this->model = $purchaseList;
		$this->warehouse = $warehouse;
		$this->supplier=$supplier;
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
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 创建采购条目页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{
		 $response = [
			'metas' => $this->metas(__FUNCTION__),
			'warehouse' => $this->warehouse->all(),
        ];
        return view($this->viewPath . 'create', $response);		
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
		$this->model->purchasestore($data);
        return redirect($this->mainIndex);		
	}
	
	/**
     * 采购条目对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$this->model->purchaseListUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	public function updateActive($id)
	{
		$data=request()->all();
		$this->model->activeUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	public function addPurchaseOrder()
	{
		$isadd=json_decode(request()->get('isadd'));
		if($isadd==1){ 
			$res=$this->model->purchaseOrderCreate();
			if($res  == true){
			return 1;
			}else{
			return 0;	
				}
		}
	}
	
	public function activeChange($id)
	{
		$res=$this->model->find($id);
		$second_supplier_id=$res->purchaseItem->second_supplier_id;
		$second_supplier=$this->supplier->find($second_supplier_id);	
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'abnormal' => $res,
			'second_supplier'=>$second_supplier,
        ];
        return view($this->viewPath . 'changeActive', $response);
			
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









