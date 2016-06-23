<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseRequireModel;
use App\Models\Product\SupplierModel;
use App\Models\StockModel;
use App\Models\PackageModel;
use App\Models\ItemModel;
use App\Models\Order\ItemModel as OrderItemModel;

class RequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requires';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id','sku'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    } 
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	
    //计算建议采购数量
    public function getNeedPurchaseNum($items){
        foreach ($items as $item) {
            //在途数量
            $purchaseItems = PurchaseItemModel::where("sku",$item['sku'])->whereIn("status",['1', '2','3'])->get();
            $zaitu_num = 0;
            foreach ($purchaseItems as $purchaseItem) {
                if(!$purchaseItem->purchaseOrder->write_off){
                    $zaitu_num += $purchaseItem->purchase_num-$purchaseItem->storage_qty-$purchaseItem->unqualified_qty;
                }
            }
            //实库存
            $itemModel = ItemModel::find($item['item_id']);
            $shi_kucun = $itemModel->all_quantity['all_quantity'];
            //虚库存
            $xu_kucun = $shi_kucun - $item['quantity'];

            //7天销量
            $sevenDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                            ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                            ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-7 day')))
                            ->where('order_items.quantity','<',5)
                            ->sum('order_items.quantity');
        
            //14天销量
            $fourteenDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                                ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                                ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-14 day')))
                                ->where('order_items.quantity','<',5)
                                ->sum('order_items.quantity');

            //30天销量
            $thirtyDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                                ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                                ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-30 day')))
                                ->where('order_items.quantity','<',5)
                                ->sum('order_items.quantity');

            //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
            if($sevenDaySellNum==0||$fourteenDaySellNum==0){
                $coefficient_status=3;
                $coefficient=1;
            }else{
                if(($sevenDaySellNum/7)/($fourteenDaySellNum/14*1.1) >=1){
                    $coefficient=1.3;
                    $coefficient_status=1;
                }elseif(($fourteenDaySellNum/14*0.9)/($sevenDaySellNum/7) >=1){
                    $coefficient=0.6;
                    $coefficient_status=2;
                }else{
                    $coefficient=1;
                    $coefficient_status=4;
                } 
            }
            
            //预交期
            $delivery=$itemModel->supplier?$itemModel->supplier->purchase_time:0;

            //采购建议数量
            if($itemModel->purchase_price > 200 && $fourteenDaySellNum <3 || $itemModel->status ==4){
                $needPurchaseNum = 0-$xu_kucun-$zaitu_num;
            }else{
                if($itemModel->purchase_price >3 && $itemModel->purchase_price <=40){
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(7+$delivery)*$coefficient-$xu_kucun-$zaitu_num;
                }elseif($itemModel->purchase_price <=3){
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(12+$delivery)*$coefficient-$xu_kucun-$zaitu_num;
                }elseif ($itemModel->purchase_price > 40) {
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(12+$delivery)*$coefficient-$xu_kucun-$zaitu_num;  
                }
            }
            
            //平均利润率

            //利润率

            //退款率

            //数组
            $trend['sevenDaySellNum'] = $sevenDaySellNum;
            $trend['fourteenDaySellNum'] = $fourteenDaySellNum;
            $trend['thirtyDaySellNum'] = $thirtyDaySellNum;
            $trend['coefficient'] = $thirtyDaySellNum;
            $trend['status'] = $thirtyDaySellNum;
            $trend['delivery'] = $thirtyDaySellNum;
            $trend['needPurchaseNum'] = $needPurchaseNum;
            //任务计划使用该函数
            $this->intoPurchaseRequire($item['item_id'],$needPurchaseNum); 
            
        }
            
            
            //退货率
            //$orderNum=orderItemModel::leftjoin('orders','orders.id','=','orderitems.order_id')->where('orders.status',)->where('orderitems.item_id',$item_id)->count();   
            //$orderNum=orderItemModel::where('item_id',$item_id)->count();
            
    }

        //加入采购需求表中
    public function intoPurchaseRequire($item_id,$needPurchaseNum){
        if(PurchaseRequireModel::where('item_id',$item_id)->count()){
            PurchaseRequireModel::where('item_id',$item_id)->update(['quantity'=>$needPurchaseNum,'status'=>0]);
        }else{
            PurchaseRequireModel::create(['quantity'=>$needPurchaseNum,'item_id'=>$item_id]);
        }       
    }
}