<?php
namespace App\Models;

use Tool;
use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchaseCrontabsModel;
use App\Models\Order\ItemModel as OrderItemModel;
use Exception;

class ItemModel extends BaseModel
{
    protected $table = 'items';

    protected $stock;

    public $searchFields = ['sku'];

    public $rules = [
        'update' => []
    ];

    protected $fillable = [
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
        'cost',
        'product_size',
        'package_size',
        'carriage_limit',
        'package_limit',
        'warehouse_id',
        'warehouse_position',
        'status',
        'is_available',
        'remark',
        'cost',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemModel', 'sku', 'sku');
    }

    public function orderItem()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'item_id');
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

    public function getAllQuantityAttribute()
    {
        return $this->stocks->sum('all_quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->stocks->sum('available_quantity');
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
            if ($flag && $this->cost && ($cost < $this->cost * 0.6 || $cost > $this->cost * 1.3)) {
                throw new Exception('入库单价不在原单价0.6-1.3范围内');
            }
            $this->update([
                'cost' => round((($this->all_quantity * $this->cost + $amount) / ($this->all_quantity + $quantity)), 3)
            ]);
            return $stock->in($quantity, $amount, $type, $relation_id, $remark);
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
        $stocks = $this->stocks->sortByDesc('available_quantity');
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
        $items = $this->all();
        $requireModel = new RequireModel();
        $array = RequireModel::groupBy('item_id')
            ->selectRaw('item_id, sum(quantity) as sum')
            ->where('is_require', 1)
            ->get()
            ->toArray();

        foreach ($array as $require_key => $require_val) {
            $requireArray[$require_val['item_id']] = $require_val['sum'];
        }

        foreach ($items as $item) {
            $data['item_id'] = $item->id;
            $data['sku'] = $item->sku;
            $data['c_name'] = $item->c_name;
            $zaitu_num = 0;
            foreach ($item->purchase as $purchaseItem) {
                if ($purchaseItem->status > 0 || $purchaseItem->status < 4) {
                    if (!$purchaseItem->purchaseOrder->write_off) {
                        $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
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
            $xu_kucun = $data['all_quantity'] - $quantity;
            //7天销量
            $sevenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.create_time', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');

            //14天销量
            $fourteenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.create_time', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');

            //30天销量
            $thirtyDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.create_time', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');

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
            $data['need_purchase_num'] = $needPurchaseNum;
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
            $refund_rate = $all_order_num ? $refund_num / $all_order_num : '100';
            //退款率
            $data['refund_rate'] = $refund_rate;
            //平均利润率
            $data['profit'] = $total_profit_num ? $total_profit_rate / $total_profit_num : '0';

            $data['status'] = $item->status;
            $data['require_create'] = 0;
            $thisModel = PurchaseCrontabsModel::where("item_id", $data['item_id'])->get()->first();

            if (array_key_exists($data['item_id'], $requireArray)) {
                $data['require_create'] = 1;
            }
            if ($thisModel) {
                $thisModel->update($data);
            } else {
                PurchaseCrontabsModel::create($data);
            }
        }
    }
}
