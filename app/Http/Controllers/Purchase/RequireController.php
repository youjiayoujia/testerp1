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
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Product\SupplierModel;
use App\Models\StockModel;
use App\Models\PackageModel;
use App\Models\Package\ItemModel;
use App\Models\ItemModel as ProductItemModel;

class RequireController extends Controller
{

    public function __construct(RequireModel $require )
    {
        $this->model = $require;
        $this->mainIndex = route('require.index');
        $this->mainTitle = '采购需求';
		$this->viewPath = 'purchase.require.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('is_require',1)->groupby('item_id')),
        ];
		$seven_time=date('Y-m-d H:i:s',strtotime('-7 day'));
		$fourteen_time=date('Y-m-d H:i:s',strtotime('-14 day'));
		$thirty_time=date('Y-m-d H:i:s',strtotime('-30 day'));
		foreach($response['data'] as $key=>$vo){
			$response['data'][$key]['order_need_num']=$this->model->where('item_id',$vo->item_id)->sum('quantity');
			$response['data'][$key]['all_quantity']=StockModel::where('item_id',$vo->item_id)->sum('all_quantity');
			$response['data'][$key]['seven_time']=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$vo->item_id)->where('packages.shipped_at','>',$seven_time)->sum('package_items.quantity');
			$response['data'][$key]['fourteen_time']=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$vo->item_id)->where('packages.shipped_at','>',$fourteen_time)->sum('package_items.quantity');
			$response['data'][$key]['thirty_time']=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$vo->item_id)->where('packages.shipped_at','>',$thirty_time)->sum('package_items.quantity');
			$response['data'][$key]['purchaseing_quantity']=PurchaseItemModel::leftjoin('purchase_orders','purchase_orders.id','=','purchase_items.purchase_order_id')->where('purchase_items.sku',$vo->sku)->where('purchase_items.status','<',4)->where('purchase_orders.examineStatus','<>',3)->sum('purchase_items.purchase_num');
			$num=PurchaseItemModel::where('active_status','>',0)->where('sku',$vo->item->sku)->count();
		$Inum=ProductItemModel::where('sku',$vo->item->sku)->where('is_sale','<>',1)->count();
		if($num >0 || $Inum>0){
			$response['data'][$key]['active_status']='不可采购';
			}else{
			$response['data'][$key]['active_status']='可采购';
				}
			}
        return view($this->viewPath . 'index', $response);
    }
	
	
	
	public function show($id){
		$model=$this->model->find($id);
		 $response = [
            'metas' => $this->metas(__FUNCTION__),
            'result' =>$this->model->where('sku',$model->sku)->get(),
        ];
        return view($this->viewPath . 'show', $response);
		}
	/**
     * AJAX创建采购单生成采购条目
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function addPurchaseOrder()
	{	
		$purchaseIds=explode(',',request()->get('purchase_ids'));
		if(request()->get('purchase_ids')){
			$needPurchases=$this->model->find($purchaseIds);
		}else{
			$needPurchases=$this->model->where('is_require',1)->groupby('item_id')->get();
		}
		foreach($needPurchases as $key=>$v){
		$all_quantity=StockModel::where('item_id',$v->item_id)->sum('all_quantity');
		$num=PurchaseItemModel::where('active_status','>',0)->where('sku',$v->item->sku)->count();
		$Inum=ProductItemModel::where('sku',$v->item->sku)->where('is_sale','<>',1)->count();
		if($num >0 || $Inum>0){
			continue;
			}
		$purchasingNum=PurchaseItemModel::leftjoin('purchase_orders','purchase_orders.id','=','purchase_items.purchase_order_id')->where('purchase_items.sku',$vo->sku)->where('purchase_orders.examineStatus','<>',3)->sum('purchase_items.purchase_num');
		$order_need_num=$this->model->where('sku',$v->sku)->sum('quantity');
		$data['type']=0;
		$data['warehouse_id']=$v->warehouse_id;
		$data['sku']=$v->sku;
		$data['supplier_id']=$v->item->supplier_id;
		$data['purchase_num']=$order_need_num - $all_quantity - $purchasingNum;
		$data['lack_num']=$data['purchase_num'];
		if($data['purchase_num']>0){
			PurchaseItemModel::create($data);
		}
		}
		$warehouse_supplier=PurchaseItemModel::select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();
			if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$data['warehouse_id']=$v['warehouse_id'];		 
				$data['supplier_id']=$v['supplier_id'];
				$supplier=SupplierModel::find($v['supplier_id']);
				$data['assigner']=$supplier->purchase_id;
				$purchaseOrder=PurchaseOrderModel::create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
					PurchaseItemModel::where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->update(['purchase_order_id'=>$purchaseOrderId]); 
				}				 
			}
			
		}
		return 1;
	}

}









