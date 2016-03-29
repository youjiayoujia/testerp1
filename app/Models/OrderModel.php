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
use App\Models\ItemModel as productItem;
use App\Models\Order\ItemModel;

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    protected $fillable = [
        'channel_id',
        'channel_account_id',
        'ordernum',
        'channel_ordernum',
        'email',
        'status',
        'active',
        'amount',
        'amount_product',
        'amount_shipping',
        'amount_coupon',
        'is_partial',
        'by_hand',
        'is_affair',
        'affairer',
        'customer_service',
        'operator',
        'payment',
        'currency',
        'rate',
        'ip',
        'address_confirm',
        'comment',
        'comment1',
        'remark',
        'import_remark',
        'shipping',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_address1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'billing_firstname',
        'billing_lastname',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zipcode',
        'billing_phone',
        'payment_date',
        'affair_time',
        'create_time',
    ];

    public $searchFields = [
        'channel_id',
        'channel_account_id',
        'ordernum',
        'email',
        'customer_service',
        'operator',
    ];

    public function rule($request)
    {
        $arr = [
            'channel_id' => 'required',
            'channel_account_id' => 'required',
            'ordernum' => 'required',
            'channel_ordernum' => 'required',
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
            'payment_date' => 'required',
        ];

        $buf = $request->all();
        $buf = $buf['arr'];
        foreach ($buf as $key => $val) {
            if ($key == 'sku') {
                foreach ($val as $k => $v) {
                    $arr['arr.sku.' . $k] = 'required';
                }
            }
            if ($key == 'quantity') {
                foreach ($val as $k => $v) {
                    $arr['arr.quantity.' . $k] = 'required';
                }
            }
            if ($key == 'price') {
                foreach ($val as $k => $v) {
                    $arr['arr.price.' . $k] = 'required';
                }
            }
            if ($key == 'status') {
                foreach ($val as $k => $v) {
                    $arr['arr.status.' . $k] = 'required';
                }
            }
            if ($key == 'ship_status') {
                foreach ($val as $k => $v) {
                    $arr['arr.ship_status.' . $k] = 'required';
                }
            }
            if ($key == 'is_gift') {
                foreach ($val as $k => $v) {
                    $arr['arr.is_gift.' . $k] = 'required';
                }
            }
        }

        return $arr;
    }

    public function orderItem()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'order_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id', 'id');
    }

    public function user_affairer()
    {
        return $this->belongsTo('App\Models\UserModel', 'affairer', 'id');
    }

    public function user_service()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service', 'id');
    }

    public function user_operator()
    {
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

    public function items()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'order_id', 'id');
    }

    public function createOrder($data)
    {
        $order = $this->create($data);
        foreach($data['items'] as $item) {
//            $item['item_id'] = productItem::where('sku', $item['sku'])->first()->id;
            $item['order_id'] = OrderModel::where(['ordernum' => $data['ordernum']])->first()->id;
            echo $item['order_id']."</br>";
            ItemModel::create($item);
//            $order->items->create($item);
        }

//        if(request()->ajax()) {
//            $len = count(array_keys(request()->input('arr.sku')));
//            $items = request()->input('arr');
//            for($i=0; $i<$len; $i++) {
//                foreach($items as $key => $val) {
//                    $val = array_values($val);
//                    $data[$key] = $val[$i];
//                }
//                $data['item_id'] = productItem::where('sku', $data['sku'])->first()->id;
//                $data['order_id'] = $order->id;
//                ItemModel::create($data);
//            }
//        }else {
//        }
//
//        foreach ($data['arr'] as $key => $item) {
//            foreach($item as $k => $v) {
//                $data['orderitems'][$k][$key] = $v;
//            }
//        }

        return $order;
    }

}