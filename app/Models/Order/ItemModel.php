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

    protected $fillable = [
        'order_item_id','order_id','sku','qty','price',
        'status','ship_status','is_gift','remark',
    ];

    public $searchFields = [
        'order_item_id', 'order_id', 'sku', 'status', 'ship_status',
    ];

    public $rules = [
        'create' => [
            'order_item_id' => 'required',
            'order_id' => 'required',
            'sku' => 'required',
        ],
        'update' => [
            'order_item_id' => 'required',
            'order_id' => 'required',
            'sku' => 'required',
        ],
    ];

    public function order() {
        return $this->hasMany('App\Models\OrderModel', 'order_id', 'order_id');
    }
}