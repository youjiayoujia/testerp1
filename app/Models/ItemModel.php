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

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
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

    /**
     * 获取库存对象
     * @param $warehousePositionId 库位id
     *
     * @return 库存对象
     *
     */
    public function getStock($warehousePosistionId)
    {
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
    public function in($warehousePosistionId, $quantity, $amount, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->in($quantity, $amount, $type, $relation_id, $remark);
        }
    }

    /**
     * hold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function hold($warehousePosistionId, $quantity)
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->hold($quantity);
        }
    }

    /**
     * unhold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function unhold($warehousePosistionId, $quantity)
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->unhold($quantity);
        }
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
    public function out($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            return $stock->out($quantity, $type, $relation_id, $remark);
        }
    }

    public function assignStock($quantity)
    {
        $result = [];
        $stocks = $this->stocks;
        if ($stocks->sum('available_quantity') >= $quantity) {
            $warehouseStocks = $stocks->groupBy('warehouse_id');
            $defaultStocks = $warehouseStocks->get($this->warehouse_id);
            //默认仓库单库位
            $stock = $defaultStocks->first(function ($key, $value) use ($quantity) {
                return $value->available_quantity >= $quantity ? $value : false;
            });
            if ($stock) {
                $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                return $result;
            }
            //默认仓库多库位
            if ($defaultStocks->sum('available_quantity') >= $quantity) {
                foreach ($defaultStocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $stock->available_quantity;
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $stock->available_quantity;
                        $quantity -= $stock->available_quantity;
                    } else {
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                        break;
                    }
                }
                return $result;
            }
            //其它仓库单库位
            $stock = $stocks->first(function ($key, $value) use ($quantity) {
                return $value->available_quantity >= $quantity ? $value : false;
            });
            if ($stock) {
                $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                return $result;
            }
            //其它仓库多库位
            $otherStocks = $warehouseStocks
                ->first(function ($key, $value) use ($quantity) {
                    return $value->sum('available_quantity') >= $quantity ? $value : false;
                });
            if ($otherStocks) {
                foreach ($otherStocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $stock->available_quantity;
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $stock->available_quantity;
                        $quantity -= $stock->available_quantity;
                    } else {
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                        $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                        break;
                    }
                }
                return $result;
            }
            //所有仓库和库位
            foreach ($stocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $stock->available_quantity;
                    $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $stock->available_quantity;
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                    $result[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                    break;
                }
            }
            return $result;
        }
        return false;
    }
}
