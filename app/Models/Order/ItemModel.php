<?php
/**
 * 产品模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/23
 * Time: 下午5:31
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    protected $table = 'order_items';

    protected $guarded = [];

    public $searchFields = [
        'order_id',
        'item_id',
        'sku',
        'status',
        'ship_status',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function getShipStatusNameAttribute()
    {
        $arr = config('order.ship_status');
        return $arr[$this->ship_status];
    }

    public function getStatusNameAttribute()
    {
        $arr = config('order.product_status');
        return $arr[$this->status];
    }

    public function getIsGiftNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_gift];
    }

}