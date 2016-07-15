<?php
/**标记发货规则设置模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:41
 */

namespace App\Models\Order;

use App\Base\BaseModel;
class OrderMarkLogicModel extends BaseModel{

    protected $table = 'order_mark_logic';

    protected $fillable = [
        'channel_id',
        'order_status',
        'order_create',
        'order_pay',
        'assign_shipping_logistics',
        'shipping_logistics_name',
        'is_upload',
        'user_id',
        'priority',
        'is_use'
    ];

    protected $searchFields = [];

    protected $rules = [
        'create'=>[

        ]
    ];
}