<?php
namespace App\Models;

use Tool;
use DB;
use App\Base\BaseModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchasesModel;
use App\Models\Purchase\PurchaseStaticsticsModel;
use App\Models\Order\ItemModel as OrderItemModel;
use App\Models\Package\ItemModel as PackageItemModel;
use App\Models\UserModel;
use App\Models\Stock\CarryOverFormsModel;
use App\Models\User\UserRoleModel;
use Exception;

class ItemModel extends BaseModel
{
    protected $table = 'items';

    protected $stock;

    public $searchFields = ['sku' =>'sku'];

    public $rules = [
        'update' => []
    ];

    protected $fillable = [
        'id',
        'product_id',
        'sku',
        'weight',
        'inventory',
        'name',
        'c_name',
        'alias_name',
        'alias_cname',
        'catalog_id',
        'supplier_id',
        'supplier_sku',
        'second_supplier_id',
        'second_supplier_sku',
        'supplier_info',
        'purchase_url',
        'purchase_price',
        'purchase_carriage',
        'package_height',
        'package_width',
        'package_length',
        'height',
        'width',
        'length',
        'cost',
        'product_size',
        'package_size',
        'carriage_limit',
        'package_limit',
        'warehouse_id',
        'warehouse_position',
        'status',
        'is_available',
        'purchase_adminer',
        'remark',
        'cost',
        'package_weight',
        'competition_url',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function purchaseAdminer()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_adminer');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function secondSupplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'second_supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function warehousePosition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel','warehouse_position');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemModel', 'sku', 'sku');
    }

    public function purchases()
    {
        return $this->hasOne('App\Models\Purchase\PurchasesModel','item_id');
    }

    public function orderItem()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'item_id');
    }

    public function skuPrepareSupplier()
    {
        return $this->belongsToMany('App\Models\Product\SupplierModel', 'item_prepare_suppliers', 'item_id','supplier_id')->withTimestamps();
    }

    public function updateItem($data)
    {
        $data['carriage_limit'] = empty($data['carriage_limit_arr']) ? '' : implode(',', $data['carriage_limit_arr']);
        $data['package_limit'] = empty($data['package_limit_arr']) ? '' : implode(',', $data['package_limit_arr']);

        $this->update($data);
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'item_id');
    }

    public function getImageAttribute()
    {
        if ($this->product->image) {
            return $this->product->image->path . $this->product->image->name;
        }
        return '/default.jpg';
    }

    //实库存
    public function getAllQuantityAttribute()
    {
        return $this->stocks->sum('all_quantity');
    }

    //虚库存
    public function getAvailableQuantityAttribute()
    {
        return $this->stocks->sum('available_quantity');
    }

    //普通在途库存
    public function getNormalTransitQuantityAttribute()
    {
        $zaitu_num = 0;
        foreach ($this->purchase as $purchaseItem) {
            if ($purchaseItem->status > 0 && $purchaseItem->status < 4) {
                if (!$purchaseItem->purchaseOrder->write_off&&$purchaseItem->purchaseOrder->type==0) {
                    $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
                }
            }
        }

        return $zaitu_num;
    }

    //特殊在途库存
    public function getSpecialTransitQuantityAttribute()
    {
        $szaitu_num = 0;
        foreach ($this->purchase as $purchaseItem) {
            if ($purchaseItem->status > 0 && $purchaseItem->status < 4) {
                if (!$purchaseItem->purchaseOrder->write_off&&$purchaseItem->purchaseOrder->type==1) {
                    $szaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
                }
            }
        }

        return $szaitu_num;
    }

    //分仓实库存和虚库存
    public function getWarehouseQuantityAttribute()
    {
        $data = [];
        $stockCollection = $this->stocks->groupBy('warehouse_id');
        foreach($stockCollection as $colleciton){
            $data[$colleciton[0]->warehouse_id]['all_quantity'] = $colleciton->sum('all_quantity');
            $data[$colleciton[0]->warehouse_id]['available_quantity'] = $colleciton->sum('available_quantity');    
        }
        $warehouses = WarehouseModel::all();
        foreach($warehouses as $warehouse){
            if(!array_key_exists($warehouse->id,$data)){
                $data[$warehouse->id]['all_quantity'] = 0;
                $data[$warehouse->id]['available_quantity'] = 0;
            }
        }

        return $data;
    }

    //分仓特采和普采在途库存
    public function getTransitQuantityAttribute()
    {
        $data = [];
        foreach ($this->purchase->groupBy('warehouse_id') as $purchaseItemCollection) {
            $warehouse_id = $purchaseItemCollection[0]->warehouse_id;
            $data[$warehouse_id]['normal'] = 0;
            $data[$warehouse_id]['special'] = 0;
            foreach ($purchaseItemCollection as $purchaseItem) {          
                if($purchaseItem->purchaseOrder->status>0&&$purchaseItem->purchaseOrder->status<4){
                    if($purchaseItem->purchaseOrder->type==0){
                        $data[$warehouse_id]['normal'] += $purchaseItem->purchase_num;
                    }else{
                        $data[$warehouse_id]['special'] += $purchaseItem->purchase_num;
                    }  
                }
            }
        }
        $warehouses = WarehouseModel::all();
        foreach($warehouses as $warehouse){
            if(!array_key_exists($warehouse->id,$data)){
                $data[$warehouse->id]['normal'] = 0;
                $data[$warehouse->id]['special'] = 0;
            }
        }
        
        return $data;
    }

    //欠货数量
    public function getOutOfStockAttribute()
    {
        $item_id = $this->id;
        $num = DB::select('select sum(package_items.quantity) as num from packages,package_items where packages.status= "NEED" and package_items.item_id = "'.$item_id.'" and 
                packages.id = package_items.package_id')[0]->num;

        return $num;
    }

    //最近一次采购时间
    public function getRecentlyPurchaseTimeAttribute()
    {
        return $this->purchase->min('created_at');
    }

    //最近缺货时间
    public function getOutOfStockTimeAttribute()
    {
        $id = $this->id;
        $firstNeedItem = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
                ->whereIn('packages.status', ['NEED'])
                ->where('package_items.item_id', $id)
                ->first(['packages.created_at']);

        if($firstNeedItem){
            $firstNeedItem = $firstNeedItem->toArray();
            $time = ceil((time()-strtotime($firstNeedItem['created_at']))/(3600*24));
        }else{
            $time = 0;
        } 
        
        return $time;
    }

    public function getStatusNameAttribute()
    {
        $config = config('item.status');
        return $config[$this->status];
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['supplier' => ['name'], 'catalog' => ['name'],'warehouse' => ['name'] ],
            'filterFields' => [],
            'filterSelects' => ['status' => config('item.status'),],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    //获得sku销量 period参数格式为 -7 day
    public function getsales($period)
    {
        //销量
        $sellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
            ->where('orders.create_time', '>', date('Y-m-d H:i:s', strtotime($period)))
            ->where('order_items.quantity', '<', 5)
            ->where('order_items.item_id', $this->id)
            ->sum('order_items.quantity');
        return $sellNum;
    }

    //计算sku采购建议数量
    public function getNeedPurchase()
    {
        //计算趋势系数
        //7天销量和14天销量
        $seven_sales = $this->getsales('-7 days');
        $fourteen_sales = $this->getsales('-14 days');
        if ($seven_sales == 0 || $fourteen_sales == 0) {
            $coefficient = 1;
        } else {
            if (($seven_sales / 7) / ($fourteen_sales / 14 * 1.1) >= 1) {
                $coefficient = 1.3; 
            } elseif (($fourteen_sales / 14 * 0.9) / ($seven_sales / 7) >= 1) {
                $coefficient = 0.6; 
            } else {
                $coefficient = 1;
            }
        }
        //虚库存
        $xu_kucun = $this->available_quantity;
        //普通在途库存
        $zaitu_num = $this->normal_transit_quantity;
        //预交期
        $delivery = $this->supplier ? $this->supplier->purchase_time : 7;
        //计算采购量
        //采购建议数量
        if ($this->purchase_price > 200 && $fourteen_sales < 3 || $this->status == 4) {
            $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
        } else {
            if ($this->purchase_price > 3 && $this->purchase_price <= 40) {
                $needPurchaseNum = ($fourteen_sales / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price <= 3) {
                $needPurchaseNum = ($fourteen_sales / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price > 40) {
                $needPurchaseNum = ($fourteen_sales / 14) * (5 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            }
        }

        return ceil($needPurchaseNum);
    }

    /**
     * 获取库存对象
     * @param $warehousePositionId 库位id
     *
     * @return 库存对象
     *
     */
    public function getStock($warehousePosistionId, $stock_id = 0)
    {
        $stock = '';
        if (!$stock_id) {
            $stock = $this->stocks()->where('warehouse_position_id', $warehousePosistionId)->first();
            if (!$stock) {
                $warehouse = PositionModel::where(['id' => $warehousePosistionId])->first()->warehouse_id;
                $len = StockModel::where(['item_id' => $this->id, 'warehouse_id' => $warehouse])->count();
                if ($len >= 2) {
                    throw new Exception('该sku对应的库位已经是2，且并没找到库位');
                }
                $stock = $this->stocks()->create([
                    'warehouse_position_id' => $warehousePosistionId,
                    'warehouse_id' => $warehouse
                ]);
            }
        } else {
            $stock = StockModel::find($stock_id);
        }

        return $stock;
    }

    /**
     * in api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     * $amount 金额
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function in(
        $warehousePosistionId,
        $quantity,
        $amount,
        $type = '',
        $relation_id = '',
        $remark = '',
        $flag = 1
    ) {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            $cost = $amount / $quantity;
            /*if ($flag && $this->cost && ($cost < $this->cost * 0.6 || $cost > $this->cost * 1.3)) {
                throw new Exception('入库单价不在原单价0.6-1.3范围内');
            }*/
            if($this->all_quantity + $quantity) {
                $this->update([
                    'cost' => round((($this->all_quantity * $this->cost + $amount) / ($this->all_quantity + $quantity)), 3)
                ]);
                return $stock->in($quantity, $amount, $type, $relation_id, $remark);
            }
        }
        return false;
    }

    /**
     * hold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function hold($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->hold($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * holdout api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function holdout($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->holdout($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * unhold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function unhold($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->unhold($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * out api
     *
     * @param
     * $warehousePoistionId 库位id
     * $quantity 数量
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function out($warehousePosistionId, $quantity, $type = '', $relation_id = '', $stock_id = 0, $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId, $stock_id);
        if ($quantity) {
            return $stock->out($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    //分配库存
    public function assignStock($quantity)
    {
        $stocks = $this->stocks->sortByDesc('available_quantity')->filter(function($query){ return $query->warehouse->is_available == 1;});
        if ($stocks->sum('available_quantity') >= $quantity) {
            $warehouseStocks = $stocks->groupBy('warehouse_id');
            //默认仓库
            $defaultStocks = $warehouseStocks->get($this->warehouse_id);
            if ($defaultStocks and $defaultStocks->sum('available_quantity') >= $quantity) {
                $gotStocks = $defaultStocks;
            } else {
                //其它仓库
                $otherStocks = $warehouseStocks
                    ->first(function ($key, $value) use ($quantity) {
                        return $value->sum('available_quantity') >= $quantity ? $value : false;
                    });
                $gotStocks = $otherStocks ? $otherStocks : $stocks;
            }
            $result = [];
            foreach ($gotStocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                    break;
                }
            }
            return $result;
        }

        return false;
    }

    //分配库存
    public function assignDefaultStock($quantity, $order_item_id)
    {
        $stocks = $this->stocks->groupBy('warehouse_id')->get($this->warehouse_id);
        if ($stocks->sum('available_quantity') >= $quantity) {
            $stocks = $stocks->sortByDesc('available_quantity');
            foreach ($stocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                    $result[$stock->warehouse_id][$stock->id]['order_item_id'] = $order_item_id;
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                    $result[$stock->warehouse_id][$stock->id]['order_item_id'] = $order_item_id;
                    break;
                }
            }
            return $result;
        }

        return false;
    }

    //匹配库存
    public function matchStock($quantity)
    {
        $result = [];
        $stocks = $this->stocks->sortByDesc('available_quantity');
        if ($stocks->sum('available_quantity') >= $quantity) {
            //单仓库
            foreach ($stocks->groupBy('warehouse_id') as $warehouseID => $warehouseStocks) {
                if ($warehouseStocks->sum('available_quantity') >= $quantity) {
                    $warehouseStock = [];
                    $matchQuantity = $quantity;
                    foreach ($warehouseStocks as $stock) {
                        if ($stock->available_quantity < $matchQuantity) {
                            $warehouseStock[$stock->id] = $this->setStockData($stock);
                            $matchQuantity -= $stock->available_quantity;
                        } else {
                            $warehouseStock[$stock->id] = $this->setStockData($stock, $matchQuantity);
                            break;
                        }
                    }
                    $result['SINGLE'][$warehouseID] = $warehouseStock;
                    continue;
                }
            }
            //多仓库
            if (!$result) {
                $warehouseStock = [];
                foreach ($stocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                        $quantity -= $stock->available_quantity;
                    } else {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                        break;
                    }
                }
                $result['MULTI'] = $warehouseStock;
            }
            return $result;
        }
        return false;
    }

    public function setStockData($stock, $quantity = null)
    {
        $quantity = $quantity ? $quantity : $stock->available_quantity;
        $stockData['item_id'] = $this->id;
        $stockData['warehouse_id'] = $stock->warehouse_id;
        $stockData['warehouse_position_id'] = $stock->warehouse_position_id;
        $stockData['quantity'] = $quantity;
        $stockData['weight'] = $this->weight * $quantity;
        return $stockData;
    }

    public function createPurchaseNeedData()
    {
        ini_set('memory_limit', '2048M');
        $items = $this->all();
        $requireModel = new RequireModel();
        foreach ($items as $item) {
            $data['item_id'] = $item->id;
            $data['sku'] = $item->sku;
            $data['c_name'] = $item->c_name;
            $zaitu_num = 0;
            foreach ($item->purchase as $purchaseItem) {
                if ($purchaseItem->status > 0 || $purchaseItem->status < 4) {
                    if($purchaseItem->purchaseOrder){
                        if (!$purchaseItem->purchaseOrder->write_off) {
                            $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
                        }
                    }  
                }
            }
            $data['zaitu_num'] = $zaitu_num;
            //实库存
            $data['all_quantity'] = $item->all_quantity;
            //可用库存
            $data['available_quantity'] = $item->available_quantity;
            //虚库存
            $quantity = $requireModel->where('is_require', 1)->where('item_id',
                $item->id)->get() ? $requireModel->where('is_require', 1)->where('item_id',
                $item->id)->sum('quantity') : 0;
            //$xu_kucun = $data['all_quantity'] - $quantity;
            $xu_kucun = $item->available_quantity;
            //7天销量
            $sevenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if($sevenDaySellNum==NULL)$sevenDaySellNum = 0;

            //14天销量
            $fourteenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if($fourteenDaySellNum==NULL)$fourteenDaySellNum = 0;

            //30天销量
            $thirtyDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if($thirtyDaySellNum==NULL)$thirtyDaySellNum = 0;

            //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
            if ($sevenDaySellNum == 0 || $fourteenDaySellNum == 0) {
                $coefficient_status = 3;
                $coefficient = 1;
            } else {
                if (($sevenDaySellNum / 7) / ($fourteenDaySellNum / 14 * 1.1) >= 1) {
                    $coefficient = 1.3;
                    $coefficient_status = 1;
                } elseif (($fourteenDaySellNum / 14 * 0.9) / ($sevenDaySellNum / 7) >= 1) {
                    $coefficient = 0.6;
                    $coefficient_status = 2;
                } else {
                    $coefficient = 1;
                    $coefficient_status = 4;
                }
            }
            $data['seven_sales'] = $sevenDaySellNum;
            $data['fourteen_sales'] = $fourteenDaySellNum;
            $data['thirty_sales'] = $thirtyDaySellNum;
            $data['thrend'] = $coefficient_status;

            //预交期
            $delivery = $this->supplier ? $this->supplier->purchase_time : 7;

            //采购建议数量
            if ($this->purchase_price > 200 && $fourteenDaySellNum < 3 || $this->status == 4) {
                $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
            } else {
                if ($item->purchase_price > 3 && $item->purchase_price <= 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($item->purchase_price <= 3) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($item->purchase_price > 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                }
            }
            $data['need_purchase_num'] = ceil($needPurchaseNum);
            //退款订单数
            $refund_num = $item->orderItem->where('is_refund', '1')->count();
            $all_order_num = 0;
            $total_profit_rate = 0;
            $total_profit_num = 0;
            foreach ($item->orderItem as $o_item) {
                if ($o_item->order) {
                    if (in_array($o_item->order->status, array('PACKED', 'SHIPPED', 'COMPLETE'))) {
                        $total_profit_rate += $o_item->order->profit_rate;
                        $total_profit_num++;
                    }
                    if (in_array($o_item->order->status,
                        array('PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'))) {
                        $all_order_num++;
                    }
                }

            }
            $refund_rate = $all_order_num ? $refund_num / $all_order_num : '0';
            //退款率
            $data['refund_rate'] = $refund_rate;
            //平均利润率
            $data['profit'] = $total_profit_num ? $total_profit_rate / $total_profit_num : '0';

            $data['status'] = $item->status?$item->status:'saleOutStopping';
            $data['require_create'] = $needPurchaseNum>0?1:0;
            $thisModel = PurchasesModel::where("item_id", $data['item_id'])->get()->first();
            $data['user_id'] = $item->purchase_adminer?$item->purchase_adminer:0;

            $firstNeedItem = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
                ->whereIn('packages.status', ['NEED'])
                ->where('package_items.item_id', $item['id'])
                ->first(['packages.created_at']);

            if($firstNeedItem){
                $firstNeedItem = $firstNeedItem->toArray();
                $data['owe_day'] = ceil((time()-strtotime($firstNeedItem['created_at']))/(3600*24));
            }else{
                $data['owe_day'] = 0;
            }
            
            if ($thisModel) {
                $thisModel->update($data);
            } else {
                PurchasesModel::create($data);
            }
        }
    }

    public function createPurchaseStaticstics()
    {
        $users = UserRoleModel::all()->where('role_id','2');
        foreach ($users as $user) {
            $data = [];
            //采购负责人
            $data['purchase_adminer'] = $user->user_id;
            //管理的SKU数
            $data['sku_num'] = $this->where('purchase_adminer',$user->user_id)->count();
            //获取时间
            $data['get_time'] = date('Y-m-d',time());
            //必须当天内下单SKU数
            $data['need_purchase_num'] = DB::select('select count(*) as num from purchases where user_id = "'.$user->user_id.'" and need_purchase_num > 0 and available_quantity+zaitu_num-seven_sales < 0 ')[0]->num;
            //15天缺货订单
            $data['fifteenday_need_order_num'] = DB::select('select count(*) as num from orders,order_items,purchases where orders.status= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "'.date('Y-m-d',time()-24*3600*15).'" ')[0]->num;
            //15天所有订单
            $data['fifteenday_total_order_num'] = DB::select('select count(*) as num from orders,order_items,purchases where orders.status!= "CANCEL" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "'.date('Y-m-d',time()-24*3600*15).'" ')[0]->num;
            //订单缺货率
            $data['need_percent'] = $data['fifteenday_total_order_num'] ? round ($data['fifteenday_need_order_num'] / $data['fifteenday_total_order_num'] ,4):0;
            //缺货总数
            $data['need_total_num'] = DB::select('select sum(order_items.quantity) as num from orders,order_items,purchases where orders.status= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id')[0]->num;
            $data['need_total_num'] = $data['need_total_num'] ? $data['need_total_num'] : 0;
            //平均缺货天数
            $data['avg_need_day'] = round(DB::select('select avg('.time().'-UNIX_TIMESTAMP(orders.created_at))/86400 as day from orders,order_items,purchases where orders.status= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id  ')[0]->day,1);
            //最长缺货天数
            $data['long_need_day'] = round(DB::select('select max('.time().'-UNIX_TIMESTAMP(orders.created_at))/86400 as day from orders,order_items,purchases where orders.status= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id  ')[0]->day,1);
            //采购单超期
            $data['purchase_order_exceed_time'] = DB::select('select count(*) as num from purchase_orders where user_id = "'.$user->user_id.'" and created_at < "'.date('Y-m-d H:i:s',time()-86400*15).'" ')[0]->num;
            //当月累计下单数量
            $data['month_order_num'] = DB::select('select count(*) as num from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "'.date('Y-m-01 00:00:00',time()).'" and order_items.price > 0')[0]->num;
            //当月累计下单总金额
            $data['month_order_money'] = DB::select('select sum(orders.amount*orders.rate) as total_price from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "'.date('Y-m-01 00:00:00',time()).'" and order_items.price > 0')[0]->total_price;
            $data['month_order_money'] = $data['month_order_money'] ? $data['month_order_money'] : 0;
            //累计运费
            $data['total_carriage'] = DB::select('select sum(total_postage) as total_postage from purchase_orders where user_id = "'.$user->user_id.'" and created_at > "'.date('Y-m-01 00:00:00',time()).'"')[0]->total_postage;
            $data['total_carriage'] = $data['total_carriage'] ? $data['total_carriage'] : 0;
            //节约成本
            $item_id_arr = DB::select('select order_items.item_id,sum(order_items.quantity) as qty from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "'.$user->user_id.'" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id group by order_items.item_id');
            $total_cost = 0;
            foreach ($item_id_arr as $item_id) {
                $stock_model = $this->find($item_id->item_id)->stocks;
                if(count($stock_model)>0){
                    $stock_id = $stock_model[0]->id;
                    $cof_model = CarryOverFormsModel::where('stock_id',$stock_id)->where('parent_id',date('m', strtotime('2011-08-25')))->get()->first();
                    if($cof_model){
                        $total_cost += $purchase_price*$item_id->qty;
                    }
                }       
            }
            $data['save_money'] = $total_cost - ($data['month_order_money'] - $data['total_carriage']);
            PurchaseStaticsticsModel::create($data);
        }
    }

    public function updateUser()
    {
        $url="http://120.24.100.157:60/api/skuInfoApi.php";
        $itemModel = $this->all();
        foreach ($itemModel as $key => $model) {
            $old_data['sku'] = $model->sku;
            $c = curl_init(); 
            curl_setopt($c, CURLOPT_URL, $url); 
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($c, CURLOPT_POSTFIELDS, $old_data);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60); 
            $buf = curl_exec($c);
            $user_array = json_decode($buf);
            $dev_id = UserModel::where('name',$user_array->dev_name)->get(['id'])->first();
            $purchase_id = UserModel::where('name',$user_array->purchase_name)->get(['id'])->first();
            $arr['purchase_adminer'] = $purchase_id?$purchase_id->id:'';
            $brr['developer'] = $dev_id?$dev_id->id:'';
            $model->update($arr);
            $model->product->spu->update($brr);
        }
        
    }

    public function updateOldData()
    {
        set_time_limit(0);
        $model = $this->where('sku','CA1205W')->get();
        foreach ($model as $key => $itemModel) {
            $erp_products_data = DB::select('select pack_method,products_with_battery,products_with_adapter,products_with_fluid,products_with_powder 
                    from erp_products_data where products_sku =  "'.$itemModel->sku.'" ');
            
            $arr = [];
            if($erp_products_data[0]->pack_method){
                $arr[] = $erp_products_data[0]->pack_method;
                $itemModel->product->wrapLimit()->sync($arr);
            }
           /* $brr = [];
            if($erp_products_data[0]->products_with_battery){
                $brr[] = 1;
            }
            if($erp_products_data[0]->products_with_adapter){
                $brr[] = 4;
            }
            if($erp_products_data[0]->products_with_fluid){
                $brr[] = 5;
            }
            if($erp_products_data[0]->products_with_powder){
                $brr[] = 2;
            }
            $itemModel->product->logisticsLimit()->sync($brr);*/
        }
    }
    
}
