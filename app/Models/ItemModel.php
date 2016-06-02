<?php
namespace App\Models;

use Tool;
use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;
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
        'is_sale',
        'remark',
        'cost',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemModel', 'sku', 'sku');
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
        return $this->product->image->path . $this->product->image->name;
    }

    public function getAllQuantityAttribute()
    {
        $data = 0;
        foreach ($this->stocks as $stock) {
            $data += $stock->all_quantity;
        }

        return $data;
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
        if(!$stock_id) {
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
    public function in($warehousePosistionId, $quantity, $amount, $type = '', $relation_id = '', $remark = '', $flag = 1)
    {
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
                            $warehouseStock[$stock->id] = $this->setStockData($stock, $matchQuantity);;
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
}
