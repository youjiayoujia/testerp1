<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-25
 * Time: 17:13
 */
namespace App\Models\Order;

use App\Base\BaseModel;
class OrderPaypalDetailModel extends BaseModel{
    protected $table = 'order_paypal_detail';

    protected $fillable = [
        'order_id',
        'paypal_id',
        'paypal_account',
        'paypal_buyer_name',
        'paypal_address',
        'paypal_country'
    ];

    protected $searchFields = [];

    protected $rules = [
        'create'=>[
        ],
        'update'=>[
        ]
    ];

}