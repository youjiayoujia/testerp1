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
use Illuminate\Support\Facades\DB;

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    protected $guarded = ['items'];

    private $canPackageStatus = ['PREPARED', 'NEED'];

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
            'customer_service' => 'required',
            'operator' => 'required',
            'address_confirm' => 'required',
            'create_time' => 'required',
            'currency' => 'required',
            'rate' => 'required',
            'transaction_number' => 'required',
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
            'shipping_phone' => 'required',
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

    public function userAffairer()
    {
        return $this->belongsTo('App\Models\UserModel', 'affairer', 'id');
    }

    public function userService()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service', 'id');
    }

    public function userOperator()
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

    public function package()
    {
        return $this->hasMany('App\Models\PackageModel', 'order_id', 'id');
    }

    public function requires()
    {
        return $this->hasMany('App\Models\RequireModel', 'order_id');
    }

    public function createOrder($data)
    {
        $order = $this->create($data);
        foreach ($data['items'] as $item) {
            $obj = ItemModel::where('sku', $item['sku'])->get();
            if (!count($obj)) {
                $item['item_id'] = 0;
                $order->update(['status' => 'ERROR']);
            } else {
                $item['item_id'] = ItemModel::where('sku', $item['sku'])->first()->id;
            }
            $order->items()->create($item);
        }

        return $order;
    }

    public function getActiveItemsAttribute()
    {
        return $this->items->where('is_active', '1');
    }

    public function canPackage()
    {
        //判断订单ACTIVE状态
        if ($this->active != 'NORMAL') {
            return false;
        }
        //判断订单状态
        if (!in_array($this->status, $this->canPackageStatus)) {
            return false;
        }

        //订单是否包含正常产品
        if ($this->active_items->count() < 1) {
            $this->status = 'ERROR';
            $this->save();
            return false;
        }
        return true;
    }

    /**
     * @param array $items
     * @return bool
     * todo:判断订单是否需要拆单先发
     * todo:判断订单是否要hold库存
     */
    public function createPackage()
    {
        if ($this->canPackage()) {
            $items = $this->setPackageItems();
            if ($items) {
                DB::beginTransaction();
                foreach ($items as $warehouseId => $packageItems) {
                    $package = [];
                    //channel
                    $package['channel_id'] = $this->channel_id;
                    $package['channel_account_id'] = $this->channel_account_id;
                    //warehouse
                    $package['warehouse_id'] = $warehouseId;
                    //type
                    $package['type'] = collect($packageItems)->count() > 1 ? 'MULTI' : (collect($packageItems)->first()['quantity'] > 1 ? 'SINGLEMULTI' : 'SINGLE');
                    $package['weight'] = collect($packageItems)->sum('weight');
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
                        foreach ($packageItems as $packageItem) {
                            $newPackageItem = $package->items()->create($packageItem);
                            try {
                                $newPackageItem->item->out(
                                    $packageItem['warehouse_position_id'],
                                    $packageItem['quantity'],
                                    'PACKAGE',
                                    $newPackageItem->id);
                                $newPackageItem->orderItem->status = 'PACKED';
                                $newPackageItem->orderItem->save();
                            } catch (Exception $e) {
                                DB::rollBack();
                            }
                        }
                    }
                }
                DB::commit();
                $this->package_times += 1;
                $this->status = 'PACKED';
                $this->save();
                return true;
            } else { //生成订单需求
                if($this->status == 'PREPARED') {
                    foreach ($this->active_items as $item) {
                        $require = [];
                        $require['item_id'] = $item->item_id;
                        $require['warehouse_id'] = 1;
                        $require['order_item_id'] = $item->id;
                        $require['sku'] = $item->sku;
                        $require['quantity'] = $item->quantity;
                        $this->requires()->create($require);
                    }
                    $this->package_times += 1;
                    $this->status = 'NEED';
                    $this->save();
                }
            }
        }
        return false;
    }

    /**
     * @param array $items
     * @return array|bool
     */
    public function setPackageItems()
    {
        if ($this->active_items->count() > 1) { //多产品
            $packageItem = $this->setMultiPackageItem();
        } else { //单产品
            $packageItem = $this->setSinglePackageItem();
        }

        return $packageItem;
    }

    //设置单产品订单包裹产品
    public function setSinglePackageItem()
    {
        $packageItem = [];
        $orderItem = $this->active_items->first();
        $stocks = $orderItem->item->assignStock($orderItem->quantity);
        if ($stocks) {
            foreach ($stocks as $warehouseId => $stock) {
                foreach ($stock as $key => $value) {
                    $packageItem[$warehouseId][$key] = $value;
                    $packageItem[$warehouseId][$key]['order_item_id'] = $orderItem->id;
                    $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                }
            }
        } else {
            return false;
        }
        return $packageItem;
    }

    //设置多产品订单包裹产品
    public function setMultiPackageItem()
    {
        $packageItem = [];
        $stocks = [];
        //根据仓库满足库存数量进行排序
        $warehouses = [];
        foreach ($this->active_items as $orderItem) {
            $itemStocks = $orderItem->item ? $orderItem->item->matchStock($orderItem->quantity) : false;
            if ($itemStocks) {
                foreach ($itemStocks as $itemStock) {
                    foreach ($itemStock as $warehouseId => $stock) {
                        if (isset($warehouses[$warehouseId])) {
                            $warehouses[$warehouseId] += 1;
                        } else {
                            $warehouses[$warehouseId] = 1;
                        }
                    }
                }
                $stocks[$orderItem->id] = $itemStocks;
            } else {
                return false;
            }
        }
        krsort($warehouses);
        //set package item
        foreach ($stocks as $orderItemId => $itemStocks) {
            foreach ($itemStocks as $type => $itemStock) {
                if ($type == 'SINGLE') {
                    $stock = collect($itemStock)->sortByDesc(function ($value, $key) use ($warehouses) {
                        return $warehouses[$key];
                    })->first();
                    foreach ($stock as $key => $value) {
                        $packageItem[$value['warehouse_id']][$key] = $value;
                        $packageItem[$value['warehouse_id']][$key]['order_item_id'] = $orderItemId;
                        $packageItem[$value['warehouse_id']][$key]['remark'] = 'REMARK';
                    }
                } else {
                    foreach ($itemStock as $warehouseId => $warehouseStock) {
                        foreach ($warehouseStock as $key => $value) {
                            $packageItem[$warehouseId][$key] = $value;
                            $packageItem[$warehouseId][$key]['order_item_id'] = $orderItemId;
                            $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                        }
                    }
                }
            }
        }
        return $packageItem;
    }

}