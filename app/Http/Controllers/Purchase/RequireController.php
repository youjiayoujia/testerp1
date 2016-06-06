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
use App\Models\Order\ItemModel as orderItemModel;

class RequireController extends Controller
{

    public function __construct(RequireModel $require,ProductItemModel $productItem )
    {
        $this->model = $require;
		$this->productItem=$productItem;
        $this->mainIndex = route('require.index');
        $this->mainTitle = '采购需求';
		$this->viewPath = 'purchase.require.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->productItem),
        ];
		foreach($response['data'] as $key=>$vo){
			$trend=$this->getNeedPurchaseNum($vo->id);
			$response['data'][$key]['order_need_num']=$this->model->where('item_id',$vo->id)->sum('quantity');
			$response['data'][$key]['all_quantity']=StockModel::where('item_id',$vo->id)->sum('available_quantity');
			$response['data'][$key]['seven_time']=$trend['sevenDaySellNum'];
			$response['data'][$key]['fourteen_time']=$trend['fourteenDaySellNum'];
			$response['data'][$key]['thirty_time']=$trend['thirtyDaySellNum'];
			$response['data'][$key]['purchaseing_quantity']=PurchaseItemModel::leftjoin('purchase_orders','purchase_orders.id','=','purchase_items.purchase_order_id')->where('purchase_items.sku',$vo->sku)->where('purchase_items.status','<',4)->where('purchase_orders.examineStatus','<>',3)->sum('purchase_items.purchase_num');
			$response['data'][$key]['sell_status']=$trend['status'];
			$response['data'][$key]['ProposedpurchaseQuantity']=$trend['ProposedpurchaseQuantity'];
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
			$needPurchases=$this->productItem->find($purchaseIds);
		}else{
			$needPurchases=$this->productItem->get();
		}
		foreach($needPurchases as $key=>$v){
		$trend=$this->getNeedPurchaseNum($v->id);
		
		$data['type']=0;
		$data['warehouse_id']=$v->warehouse_id;
		$data['sku']=$v->sku;
		$data['supplier_id']=$v->supplier_id;
		$data['purchase_num']=$trend['ProposedpurchaseQuantity'];
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

	//计算建议采购数量
	public function getNeedPurchaseNum($item_id){
			$itemModel=ProductItemModel::find($item_id);
			$bulkPurchasNum=$this->model->where('quantity','>',5)->where('is_require',1)->where('item_id',$item_id)->sum('quantity');
			$bulkPurchasNum=$bulkPurchasNum > 0 ? $bulkPurchasNum : 0;
			$trend='';
			if($itemModel->is_sale ==1 ||$itemModel->is_sale ==4){
			$seven_time=date('Y-m-d H:i:s',strtotime('-7 day'));
			$fourteen_time=date('Y-m-d H:i:s',strtotime('-14 day'));
			$thirty_time=date('Y-m-d H:i:s',strtotime('-30 day'));
			$sevenDaySellNum=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$item_id)->where('packages.shipped_at','>',$seven_time)->where('package_items.quantity','<',5)->sum('package_items.quantity');
			$fourteenDaySellNum=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$item_id)->where('packages.shipped_at','>',$fourteen_time)->where('package_items.quantity','<',5)->sum('package_items.quantity');
			$thirtyDaySellNum=ItemModel::leftjoin('packages','package_items.package_id','=','packages.id')->where('package_items.item_id',$item_id)->where('package_items.quantity','<',5)->where('packages.shipped_at','>',$thirty_time)->sum('package_items.quantity');
			$trend['sevenDaySellNum']=$sevenDaySellNum ? $sevenDaySellNum : 0;
			$trend['fourteenDaySellNum']=$fourteenDaySellNum ? $fourteenDaySellNum : 0;
			$trend['thirtyDaySellNum']=$thirtyDaySellNum ? $thirtyDaySellNum : 0;
			
			
			//开始计算趋势系数
			if($sevenDaySellNum == 0 || $fourteenDaySellNum== 0){
				$trend['coefficient']=1;
				$trend['status']=4;
				}else{
				if(($sevenDaySellNum/7)/($fourteenDaySellNum/14*1.1) >=1){
					$trend['coefficient']=1.3;
					$trend['status']=1;
				}elseif(($fourteenDaySellNum/14*0.9)/($sevenDaySellNum/7) >=1){
					$trend['coefficient']=0.6;
					$trend['status']=2;
				}else{
					$trend['coefficient']=1;
					$trend['status']=3;
				}
			}
			}
			//计算交期
			$trend['delivery']=$itemModel->supplier->purchase_time;
			//计算可用库存
			$availableQuantity=StockModel::where('item_id',$item_id)->sum('available_quantity');
			//计算可用在途数量
			$purchaseQuantity=PurchaseItemModel::where('sku',$itemModel->sku)->where('status','<',5)->sum('purchase_num');
			//异常Item不采购
			$itemActiveNum=PurchaseItemModel::where('sku',$itemModel->sku)->where('active_status','>',0)->count();
			//采购建议数量
			if($itemModel->is_sale == 1 && $itemActiveNum == 0){
			if($itemModel->purchase_price >3 && $itemModel->purchase_price <=40){
					$trend['ProposedpurchaseQuantity']=$fourteenDaySellNum/14*(7+$trend['delivery'])*$trend['coefficient']-$availableQuantity-$purchaseQuantity+$bulkPurchasNum;
				}elseif($itemModel->purchase_price >40){
					if($itemModel->purchase_price >200 && $fourteenDaySellNum < 3){
						$available_quantity=StockModel::where('item_id',$item_id)->sum('available_quantity');
						$purchasingNum=PurchaseItemModel::leftjoin('purchase_orders','purchase_orders.id','=','purchase_items.purchase_order_id')->where('purchase_items.sku',$itemModel->sku)->where('purchase_items.status','<',4)->where('purchase_orders.examineStatus','<>',3)->sum('purchase_items.purchase_num');
						$needNum=$this->model->where('item_id',$item_id)->sum('quantity');
						$trend['ProposedpurchaseQuantity']=$needNum-$purchasingNum-$available_quantity;
						}else{
					$trend['ProposedpurchaseQuantity']=$fourteenDaySellNum/14*(5+$trend['delivery'])*$trend['coefficient']-$availableQuantity-$purchaseQuantity+$bulkPurchasNum;
					}
				}elseif($itemModel->purchase_price <=3){
					$trend['ProposedpurchaseQuantity']=$fourteenDaySellNum/14*(12+$trend['delivery'])*$trend['coefficient']-$availableQuantity-$purchaseQuantity+$bulkPurchasNum;
			}
			}else{
				if($itemModel->is_sale == 4){
					$available_quantity=StockModel::where('item_id',$item_id)->sum('available_quantity');
						$purchasingNum=PurchaseItemModel::leftjoin('purchase_orders','purchase_orders.id','=','purchase_items.purchase_order_id')->where('purchase_items.sku',$itemModel->sku)->where('purchase_items.status','<',4)->where('purchase_orders.examineStatus','<>',3)->sum('purchase_items.purchase_num');
						$needNum=$this->model->where('item_id',$item_id)->sum('quantity');
						$trend['ProposedpurchaseQuantity']=$needNum-$purchasingNum-$available_quantity;
				}else{
				$trend['ProposedpurchaseQuantity']=0;
				}
			}
			//退货率
			//$orderNum=orderItemModel::leftjoin('orders','orders.id','=','orderitems.order_id')->where('orders.status',)->where('orderitems.item_id',$item_id)->count();	
			//$orderNum=orderItemModel::where('item_id',$item_id)->count();
			return $trend;
		}
}









