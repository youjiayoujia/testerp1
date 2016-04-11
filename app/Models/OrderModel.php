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

use Tool;
use App\Base\BaseModel;

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    protected $guarded = [];

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

    public function packages()
    {
        return $this->hasMany('App\Models\PackageModel', 'order_id', 'id');
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

        foreach ($data['items'] as $item) {
//            $item['item_id'] = productItem::where('sku', $item['sku'])->first()->id;
            $order->items()->create($item);
        }

        return $order;
    }

    /**
     * @param array $items
     * @return bool
     * todo:更新订单状态
     * todo:判断订单是否要生成包裹
     * todo:订单优先级
     * todo:判断订单是否需要拆单先发
     * todo:判断订单是否要hold库存
     */
    public function createPackage($items = [])
    {
        $items = $this->getStocks($items);
        $items = $this->setPackageItems($items);
        if ($items) {
            foreach ($items as $warehouseId => $packageItems) {
                $package = [];
                //channel
                $package['channel_account_id'] = $this->channel_account_id;
                //warehouse
                $package['warehouse_id'] = $warehouseId;
                //assigner
                $package['assigner_id'] = 1;
                //type
                $package['type'] = $this->judgePacketType($packageItems);
                $package['email'] = $this->email;
                $package['shipping_firstname'] = $this->shipping_firstname;
                $package['shipping_lastname'] = $this->shipping_lastname;
                $package['shipping_address'] = $this->shipping_address;
                $package['shipping_address1'] = $this->shipping_address1;
                $package['shipping_city'] = $this->shipping_city;
                $package['shipping_state'] = $this->shipping_state;
                $package['shipping_country'] = $this->shipping_country;
                $package['shipping_zipcode'] = $this->shipping_zipcode;
                $package['shipping_phone'] = $this->shipping_phone;
                $package = $this->packages()->create($package);
                if ($package) {
                    $packageWeight = 0;
                    foreach ($packageItems as $packageItem) {
                        $packageWeight += $packageItem['weight'];
                        $package->items()->create($packageItem);
                    }
                    $package->update(['weight' => $packageWeight]);
                }
            }
            return true;
        }
        return false;
    }

    public function getStocks($items)
    {
        if ($items) {
            $able = [];
            foreach ($items as $key => $item) {
                if ($item['quantity'] > 0) {
                    $orderItem = $this->items->find($item['order_item_id']);
                    //package item quantity must not more than order item
                    if ($item['quantity'] > $orderItem->quantity) {
                        exit('包裹产品数量不能大于订单产品数量');
                    }
//                    $stocks = $orderItem->item->assignStock($item['quantity']);
                    $stocks = $orderItem->item->stocks();
                    $defaultWarehouseStock = $stocks
                        ->where('warehouse_id', $orderItem->item->product->warehouse_id);
                    $otherWarehouseStock = $stocks
                        ->where('warehouse_id', '<>', $orderItem->item->product->warehouse_id);
                    //获取默认仓库单库位库存
                    $defaultSingleStock = $defaultWarehouseStock
                        ->where('available_quantity', '>=', $item['quantity'])->first();
                    if ($defaultSingleStock) {
                        $able[$key][] = [$defaultSingleStock];
                    }
                    //获取默认仓库多库位库存
                    $defaultMultiStocksSum = $defaultWarehouseStock->sum('available_quantity');
                    if ($defaultMultiStocksSum > $item['quantity']) {
                        foreach ($defaultWarehouseStock as $defaultMultiStock) {
                            $able[$key][] = [$defaultMultiStock];
                        }
                    }
                    //获取其它仓库单库位库存
                    $otherSingleStock = $otherWarehouseStock
                        ->where('available_quantity', '>=', $item['quantity'])->first();
                    if ($otherSingleStock) {
                        $able[$key][] = [$otherSingleStock];
                    }
                    //获取其它仓库多库位库存
                    $otherMultiStocksSum = $stocks
                        ->groupBy('warehouse_id');
                }
            }
        }
    }

    /**
     * @param array $items
     * @return array|bool
     * todo:默认仓库,默认同仓库
     * todo:hold库存,unhold库存
     * todo:生成采购需求
     */
    public function setPackageItems($items = [])
    {
        $packageItem = [];
        if ($items) {
            foreach ($items as $key => $item) {
                if ($item['quantity'] > 0) {
                    $orderItem = $this->items->find($item['order_item_id']);
                    //package item quantity must not more than order item
                    if ($item['quantity'] > $orderItem->quantity) {
                        exit('包裹产品数量不能大于订单产品数量');
                    }
//                    $stocks = $orderItem->item->assignStock($item['quantity']);
                    $stocks = $orderItem->item->assignStock($item->quantity);
                    if ($stocks) {
                        foreach ($stocks as $warehouseId => $stock) {
                            foreach ($stock as $warehousePositionId => $value) {
                                $key = $orderItem->item_id . '-' . $warehousePositionId;
                                $packageItem[$warehouseId][$key]['item_id'] = $orderItem->item_id;
                                $packageItem[$warehouseId][$key]['warehouse_position_id'] = $warehousePositionId;
                                $packageItem[$warehouseId][$key]['order_item_id'] = $orderItem->id;
                                $packageItem[$warehouseId][$key]['quantity'] = $value['quantity'];
                                $packageItem[$warehouseId][$key]['weight'] = $value['weight'];
                                $packageItem[$warehouseId][$key]['remark'] = $item['remark'];
                            }
                        }
                    } else {
                        return false;
                    }
                }
            }
        } else {
            foreach ($this->items as $key => $item) {
                $stocks = $item->item->assignStock($item->quantity);
                if ($stocks) {
                    foreach ($stocks as $warehouseId => $stock) {
                        foreach ($stock as $warehousePositionId => $value) {
                            $key = $item->item_id . '-' . $warehousePositionId;
                            $packageItem[$warehouseId][$key]['item_id'] = $item->item_id;
                            $packageItem[$warehouseId][$key]['warehouse_position_id'] = $warehousePositionId;
                            $packageItem[$warehouseId][$key]['order_item_id'] = $item->id;
                            $packageItem[$warehouseId][$key]['quantity'] = $value['quantity'];
                            $packageItem[$warehouseId][$key]['weight'] = $value['weight'];
                            $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                        }
                    }
                } else {
                    return false;
                }
            }
        }
        return $packageItem;
    }

    public function judgePacketType($items)
    {
        $items = collect($items);
        if ($items->count() > 1) {
            return 'MULTI';
        }
        return $items->first()['quantity'] > 1 ? 'SINGLEMULTI' : 'SINGLE';
    }

}