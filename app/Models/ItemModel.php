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

    /**
     * 通过item_id和quantity自动分配库存
     *
     * @param $quantity
     * @return array
     *
     */
    public function assignStock($quantity)
    {
        $stocks = [];
        $stock = $this->stocks->where('available_quantity', '>=', $quantity)->first();
        if ($stock) {
            $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
            $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
        } else {
            if ($this->stocks->sum('available_quantity') >= $quantity) {
                foreach ($this->stocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $stock->available_quantity;
                        $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $stock->available_quantity;
                        $quantity -= $stock->available_quantity;
                    } else {
                        $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['quantity'] = $quantity;
                        $stocks[$stock->warehouse_id][$stock->warehouse_position_id]['weight'] = $this->weight * $quantity;
                        break;
                    }
                }
            }
        }
        return $stocks;
    }
}
