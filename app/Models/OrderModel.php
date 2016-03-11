<?php
/**
 * 订单模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/18
 * Time: 下午5:57
 */

namespace App\Models;

use App\Base\BaseModel;

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    protected $fillable = [
        'channel_id','channel_account_id','order_number',
        'channel_order_number','email','status','active','amount','amount_product',
        'amount_shipping','amount_coupon','is_partial','by_hand','is_affair','affairer',
        'customer_service','operator','payment','currency','rate','ip','address_confirm',
        'comment','comment1','remark','import_remark','shipping','shipping_firstname',
        'shipping_lastname','shipping_address','shipping_address1','shipping_city',
        'shipping_state','shipping_country','shipping_zipcode','shipping_phone',
        'billing_firstname','billing_lastname','billing_address','billing_city',
        'billing_state','billing_country','billing_zipcode','billing_phone',
        'payment_date','affair_time','create_time',
    ];

    public $searchFields = [
        'channel_id', 'channel_account_id', 'order_number',
        'email', 'customer_service', 'operator',
    ];

    public function rule($request)
    {
        $arr = [
            'channel_id' => 'required',
            'channel_account_id' => 'required',
            'order_number' => 'required',
            'channel_order_number' => 'required',
            'email' => 'required',
            'status' => 'required',
            'active' => 'required',
            'affairer' => 'required',
            'customer_service' => 'required',
            'operator' => 'required',
            'ip' => 'required',
            'address_confirm' => 'required',
            'affair_time' => 'required',
            'create_time' => 'required',
            'currency' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'amount_product' => 'required',
            'amount_shipping' => 'required',
            'amount_coupon' => 'required',
            'shipping' => 'required',
            'shipping_firstname' => 'required',
            'shipping_lastname' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'shipping_state' => 'required',
            'shipping_country' => 'required',
            'shipping_zipcode' => 'required',
            'shipping_phone' => 'required|digits_between:8,11',
            'payment' => 'required',
            'billing_firstname' => 'required',
            'billing_lastname' => 'required',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_country' => 'required',
            'billing_zipcode' => 'required',
            'billing_phone' => 'required|digits_between:8,11',
            'payment_date' => 'required',
        ];

        $buf = $request->all();
        $buf = $buf['arr'];
        foreach($buf as $key => $val)
        {
            if($key == 'sku')
                foreach($val as $k => $v)
                {
                    $arr['arr.sku.'.$k] ='required';
                }
            if($key == 'quantity')
                foreach($val as $k => $v)
                {
                    $arr['arr.quantity.'.$k] ='required';
                }
            if($key == 'price')
                foreach($val as $k => $v)
                {
                    $arr['arr.price.'.$k] ='required';
                }
            if($key == 'status')
                foreach($val as $k => $v)
                {
                    $arr['arr.status.'.$k] = 'required';
                }
            if($key == 'ship_status')
                foreach($val as $k => $v)
                {
                    $arr['arr.ship_status.'.$k] = 'required';
                }
            if($key == 'is_gift')
                foreach($val as $k => $v)
                {
                    $arr['arr.is_gift.'.$k] = 'required';
                }
        }

        return $arr;
    }

    public function orderItem() {
        return $this->hasMany('App\Models\Order\ItemModel', 'order_id', 'id');
    }

    public function channel() {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function channelAccount() {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id', 'id');
    }

    public function user_affairer() {
        return $this->belongsTo('App\Models\UserModel', 'affairer', 'id');
    }

    public function user_service() {
        return $this->belongsTo('App\Models\UserModel', 'customer_service', 'id');
    }

    public function user_operator() {
        return $this->belongsTo('App\Models\UserModel', 'operator', 'id');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('order.status');
        return $arr[$this->status];
    }

    public function getActiveNameAttribute()
    {
        $arr = config('order.active');
        return $arr[$this->active];
    }

    public function getIsPartialNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_partial];
    }

    public function getByHandNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->by_hand];
    }

    public function getIsAffairNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_affair];
    }

    public function getAddressConfirmNameAttribute()
    {
        $arr = config('order.address');
        return $arr[$this->address_confirm];
    }

}