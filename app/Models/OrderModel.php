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

use Exception;
use Tool;
use App\Base\BaseModel;
use App\Models\ItemModel;
use App\Models\Channel\ProductModel as ChannelProduct;
use Illuminate\Support\Facades\DB;
use App\Models\Order\RefundModel;

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

    public function items()
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

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
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

    public function remarks()
    {
        return $this->hasMany('App\Models\Order\RemarkModel', 'order_id', 'id');
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\Order\RefundModel', 'order_id', 'id');
    }

    public function requires()
    {
        return $this->hasMany('App\Models\RequireModel', 'order_id');
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

    public function getWithdrawNameAttribute()
    {
        $arr = config('order.withdraw');
        return $arr[$this->withdraw];
    }

    /**
     * 订单成本获取器
     * @return int
     */
    public function getAllItemCostAttribute()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->item->cost * $item->item->quantity;
        }
        return $total;
    }

    public function getPartialOverAttribute()
    {
        foreach ($this->items as $item) {
            if ($item->split_quantity != $item->quantity) {
                return false;
            }
        }
        return true;
    }

    public function getOrderQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getLogisticsFeeAttribute()
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total += $package->cost + $package->cost1;
        }
        return $total;
    }

    public function getActiveItemsAttribute()
    {
        return $this->items->where('is_active', '1');
    }

    public function refundCreate($data, $file = null)
    {
        $path = 'uploads/refund' . '/' . $data['order_id'] . '/';
        if ($file->getClientOriginalName()) {
            $data['image'] = $path . time() . '.' . $file->getClientOriginalExtension();
            $file->move($path, time() . '.' . $file->getClientOriginalExtension());
            return RefundModel::create($data);
        }
        return 1;
    }

    //todo:黑名单逻辑
    public function checkBlack()
    {
        $isBlack = 'confirm';
        if ($isBlack == 'confirm') {
            return true;
        }
        return false;
    }

    public function createOrder($data)
    {
        $last = $this->all()->last();
        $data['ordernum'] = $last ? $last->id + 1 : 1;
        if ($this->checkBlack()) {
            $data['status'] = 'REVIEW';
        }
        $order = $this->create($data);
        foreach ($data['items'] as $orderItem) {
            $item = ItemModel::where('sku', $orderItem['sku'])->first();
            if ($item) {
                $orderItem['item_id'] = $item->id;
            } else {
                $channelProduct = ChannelProduct::where('channel_sku', $orderItem['channel_sku'])->first();
                if ($channelProduct) {
                    $orderItem['item_id'] = $channelProduct->item->id;
                }
            }
            if (!isset($orderItem['item_id'])) {
                $orderItem['item_id'] = 0;
                $order->update(['status' => 'REVIEW']);
                $order->remark($orderItem['channel_sku'] . '找不到对应产品.');
            }
            $order->items()->create($orderItem);
        }
        if ($this->checkBlack()) {
            $order->update(['status' => 'REVIEW']);
            $order->remark('黑名单订单.');
        }
        return $order;
    }

    //todo: Update order
    public function updateOrder($data)
    {
        unset($data['items']);
        $order = $this->update($data);
        return $order;
    }

    public function remark($remark, $user_id = 0)
    {
        return $this->remarks()->create(['remark' => $remark, 'user_id' => $user_id]);
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
            $this->status = 'REVIEW';
            $this->save();
            return false;
        }
        return true;
    }

    /**
     * @param array $items
     * @return bool
     */
    public function createPackage()
    {
        if ($this->canPackage()) {
            $items = $this->setPackageItems();
            if ($items) {
                return $this->createPackageDetail($items);
            } else { //生成订单需求
                if ($this->status == 'PREPARED') {
                    foreach ($this->active_items as $item) {
                        $require = [];
                        $require['item_id'] = $item->item_id;
                        $require['warehouse_id'] = $item->item->warehouse_id;
                        $require['order_item_id'] = $item->id;
                        $require['sku'] = $item->sku;
                        $require['quantity'] = $item->quantity;
                        $this->requires()->create($require);
                    }
                    $this->package_times += 1;
                    $this->status = 'NEED';
                    $this->save();
                } elseif ($this->status == 'NEED') {
                    if (strtotime($this->created_at) < strtotime('-3 days')) {
                        $arr = $this->explodeOrder();
                        if ($arr) {
                            $this->is_partial = 1;
                            $this->package_times += 1;
                            $this->save();
                            $this->createPackageDetail($arr, 0);
                            return true;
                        }
                    }
                    $this->package_times += 1;
                    $this->save();
                }
            }
        }

        return false;
    }

    public function createPackageDetail($items, $flag = 1)
    {
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
                foreach ($packageItems as $key => $packageItem) {
                    $newPackageItem = $package->items()->create($packageItem);
                    DB::beginTransaction();
                    try {
                        $newPackageItem->item->out(
                            $packageItem['warehouse_position_id'],
                            $packageItem['quantity'],
                            'PACKAGE',
                            $newPackageItem->id,
                            $key);
                        if ($flag == 1) {
                            $newPackageItem->orderItem->status = 'PACKED';
                        }
                        $newPackageItem->orderItem->split_quantity += $newPackageItem->quantity;
                        $newPackageItem->orderItem->save();
                    } catch (Exception $e) {
                        DB::rollBack();
                    }
                    DB::commit();
                }
            }
        }
        if ($flag == 1) {
            $this->status = 'PACKED';
        } else {
            $this->split_times += 1;
        }
        $this->save();
    }

    public function explodeOrder()
    {
        $arr = $this->orderStockDiff($this->orderNeedArray());
        $sum = $this->atLeastTimes($arr);
        if ($this->split_times > (4 - $sum)) {
            return false;
        }
        $stocks = [];
        foreach ($arr as $key => $value) {
            if (!($arr[$key]['allocateSum'] >= 5 && $arr[$key]['allocateSum'] / $arr[$key]['sum'] >= 0.5 || $arr[$key]['allocateSum'] < 5 && $arr[$key]['allocateSum'] == $arr[$key]['sum'])) {
                continue;
            }
            foreach ($value as $k => $v) {
                if (!is_array($v)) {
                    continue;
                }
                if ($v['allocateQuantity']) {
                    $defaultStocks = ItemModel::find($k)->assignDefaultStock($v['allocateQuantity'],
                        $v['order_item_id']);
                    if (array_key_exists($key, $stocks)) {
                        $stocks[$key] = $stocks[$key] + $defaultStocks[$key];
                    } else {
                        $stocks += $defaultStocks;
                    }
                }
            }
        }

        return $stocks;
    }

    public function atLeastTimes($arr)
    {
        $sum = 0;
        foreach ($arr as $key => $value) {
            if ($value['sum'] == $value['allocateSum'] || $value['allocateSum'] == 0) {
                $sum += 1;
            } else {
                $sum += 2;
            }
        }

        return $sum;
    }

    public function orderNeedArray()
    {
        $arr = [];
        foreach ($this->items as $orderItem) {
            $item = $orderItem->item;
            $needQuantity = $orderItem->quantity - $orderItem->split_quantity;
            if ($needQuantity) {
                if (!array_key_exists($item->warehouse_id, $arr)) {
                    $arr[$item->warehouse_id] = [];
                    $arr[$item->warehouse_id]['sum'] = 0;
                    if (!array_key_exists($orderItem->item_id, $arr[$item->warehouse_id])) {
                        $arr[$item->warehouse_id][$orderItem->item_id]['quantity'] = $needQuantity;
                        $arr[$item->warehouse_id][$orderItem->item_id]['order_item_id'] = $orderItem->id;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    } else {
                        $arr[$item->warehouse_id][$orderItem->item_id]['quantity'] += $needQuantity;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    }
                } else {
                    if (!array_key_exists($orderItem->item_id, $arr[$item->warehouse_id])) {
                        $arr[$item->warehouse_id][$orderItem->item_id]['quantity'] = $needQuantity;
                        $arr[$item->warehouse_id][$orderItem->item_id]['order_item_id'] = $orderItem->id;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    } else {
                        $arr[$item->warehouse_id][$orderItem->item_id]['quantity'] += $needQuantity;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    }
                }
            }
        }

        return $arr;
    }

    public function orderStockDiff($arr)
    {
        foreach ($arr as $warehouseId => $singleWarehouseInfo) {
            $arr[$warehouseId]['allocateSum'] = 0;
            foreach ($singleWarehouseInfo as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                foreach ($value as $k => $v) {
                    $stocks = StockModel::where(['item_id' => $key, 'warehouse_id' => $warehouseId])->get();
                    if (!count($stocks)) {
                        $arr[$warehouseId][$key]['allocateQuantity'] = 0;
                    } else {
                        $stock_sum = $stocks->sum('available_quantity');
                        $arr[$warehouseId][$key]['allocateQuantity'] = ($stock_sum <= $arr[$warehouseId][$key]['quantity']) ? $stock_sum : $arr[$warehouseId][$key]['quantity'];
                        $arr[$warehouseId]['allocateSum'] += $arr[$warehouseId][$key]['allocateQuantity'];
                    }
                    continue 2;
                }
            }
        }

        return $arr;
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
        $quantity = $orderItem->quantity - $orderItem->split_quantity;
        if (!$quantity) {
            return false;
        }
        $stocks = $orderItem->item->assignStock($quantity);
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
            $quantity = $orderItem->quantity - $orderItem->split_quantity;
            if (!$quantity) {
                continue;
            }
            $itemStocks = $orderItem->item ? $orderItem->item->matchStock($quantity) : false;
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