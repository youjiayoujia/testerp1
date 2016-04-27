<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    protected $table = 'package_items';

    protected $fillable = [
        'item_id',
        'warehouse_position_id',
        'package_id',
        'order_item_id',
        'quantity',
        'picked_quantity',
        'remark',
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\PackageModel', 'package_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }

    public function orderItem()
    {
        return $this->belongsTo('App\Models\Order\ItemModel', 'order_item_id');
    }

    public function warehousePosition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id');
    }
}