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
use Exception;
use Storage;
use App\Models\CurrencyModel;
use App\Base\BaseModel;
use App\Models\ItemModel;
use App\Models\Order\RefundModel;
use App\Models\Channel\ProductModel as ChannelProduct;
use App\Models\Order\BlacklistModel;
use Illuminate\Support\Facades\DB;
use App\Models\Oversea\ChannelSaleModel;

class OrderModel extends BaseModel
{
    public $table = 'orders';

    public $guarded = ['items', 'remark'];

    public $fillable = [
        'id',
        'channel_id',
        'channel_account_id',
        'ordernum',
        'channel_ordernum',
        'channel_listnum',
        'by_id',
        'email',
        'status',
        'active',
        'order_is_alert',
        'amount',
        'gross_margin',
        'profit_rate',
        'amount_product',
        'amount_shipping',
        'amount_coupon',
        'transaction_number',
        'customer_service',
        'operator',
        'payment',
        'currency',
        'rate',
        'address_confirm',
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
        'customer_remark',
        'withdraw_reason',
        'withdraw',
        'cele_admin',
        'priority',
        'package_times',
        'split_times',
        'split_quantity',
        'fulfill_by',
        'blacklist',
        'platform',
        'aliexpress_loginId',
        'payment_date',
        'create_time',
        'is_chinese',
        'orders_expired_time',
        'created_at',
    ];

    private $canPackageStatus = ['PREPARED'];

    public $searchFields = ['ordernum' => '订单号', 'channel_ordernum' => '渠道订单号', 'email' => '邮箱', 'by_id' => '买家ID'];

    /**
     * 退款rules
     */
    public $rules = [
        'create' => [
            'refund_amount' => 'required',
            'price' => 'required',
            'refund_currency' => 'required',
            'refund' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'image' => 'required',
        ],
    ];

    public function getOrderWeightAttribute()
    {
        $items = $this->items;
        $weight = 0;
        foreach($items as $item) {
            $weight += $item->item->weight * $item->quantity;
        }

        return $weight;
    }

    public function rule($request)
    {
        $arr = [
            'channel_id' => 'required',
            'channel_account_id' => 'required',
            'ordernum' => 'required',
            'channel_ordernum' => 'required',
            'status' => 'required',
            'active' => 'required',
            'customer_service' => 'required',
            'operator' => 'required',
            'address_confirm' => 'required',
            'create_time' => 'required',
            'currency' => 'required',
            'transaction_number' => 'required',
            'amount' => 'required',
            'amount_product' => 'required',
//            'amount_shipping' => 'required',
            'amount_coupon' => 'required',
            'shipping_firstname' => 'required',
//            'shipping_lastname' => 'required',
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

    public function updateRule($request)
    {
        $arr = [
//            'amount_shipping' => 'required',
            'shipping_firstname' => 'required',
//            'shipping_lastname' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'shipping_state' => 'required',
            'shipping_country' => 'required',
            'shipping_zipcode' => 'required',
            'shipping_phone' => 'required',
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

    public function getMixedSearchAttribute()
    {
        foreach (ChannelModel::all() as $channel) {
            $arr[$channel->name] = $channel->name;
        }
        return [
            'filterFields' => [
                'ordernum',
                'channel_ordernum',
                'email',
                'by_id',
                'shipping_firstname',
                'currency',
                'profit_rate'
            ],
            'filterSelects' => [
                'status' => config('order.status'),
                'active' => config('order.active'),
                'is_chinese' => config('order.is_chinese')
            ],
            'sectionSelect' => [
                'price' => ['amount'],
                'time' => ['created_at']
            ],
            'relatedSearchFields' => [
                'country' => ['code'],
                'items' => ['sku'],
                'channelAccount' => ['alias'],
                'userService' => ['name']
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => $arr],
                'items' => ['item_status' => config('item.status')],
            ]
        ];
    }

    public function unpaidOrder()
    {
        return $this->belongsTo('App\Models\Order\UnpaidOrderModel', 'by_id', 'ordernum');
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

    public function currency()
    {
        return $this->belongsTo('App\Models\CurrencyModel', 'currency', 'code');
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

    public function ebayMessageList(){
        return $this->hasMany('App\Models\Message\SendEbayMessageListModel','order_id','id');
    }

    public function getStatusNameAttribute()
    {
        $config = config('order.status');
        return isset($config[$this->status]) ? $config[$this->status] : '';
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'REVIEW':
                $color = 'danger';
                break;
            case 'CANCEL':
                $color = 'active';
                break;
            case 'NEED':
                $color = 'warning';
                break;
            case 'COMPLETE':
                $color = 'success';
                break;
            case 'SHIPPED':
                $color = 'success';
                break;
            case 'UNPAID':
                $color = '';
                break;
            default:
                $color = 'info';
                break;
        }
        return $color;
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

    public function getLogisticsAttribute()
    {
        $logistics = '';
        foreach($this->packages as $package) {
            $logisticsName = $package->logistics ? $package->logistics->code : '';
            $logistics .= $logisticsName . ' ';
        }

        return $logistics;
    }

    public function getCodeAttribute()
    {
        $code = '';
        foreach($this->packages as $package) {
            $trackingNo = $package->tracking_no;
            $code .= $trackingNo . ' ';
        }

        return $code;
    }

    /**
     * 订单成本获取器
     * @return int
     */
    public function getAllItemCostAttribute()
    {
        $total = 0;
        $currency = CurrencyModel::where('code', 'RMB')->first()->rate;
        foreach ($this->items as $item) {
            $total += $item->item->purchase_price * $item->quantity;
        }
        return $total * $currency;
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
            $total += $package->calculateLogisticsFee();
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
        if ($file != '' && $file->getClientOriginalName()) {
            $data['image'] = $path . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['image'], file_get_contents($file->getRealPath()));
            if ($data['type'] == 'FULL') {
                $total = 0;
                foreach ($data['arr']['id'] as $id) {
                    $orderItem = $this->items->find($id);
                    $orderItem->update(['is_refund' => 1]);
                    $total = $orderItem['price'] * $orderItem['quantity'] + $total;
                }
                $data['refund_amount'] = $total;
                $data['price'] = $total;
            }
            if ($data['type'] == 'PARTIAL') {
                foreach ($data['tribute_id'] as $id) {
                    $orderItem = $this->items->find($id);
                    $orderItem->update(['is_refund' => 1]);
                }
            }
            $data['customer_id'] = request()->user()->id;
            $refund = new RefundModel;
            $refund_new=$refund->create($data);
            if ($data['type'] == 'FULL') {
                foreach ($data['arr']['id'] as $fullid) {
                    $orderItem = $this->items->find($fullid);
                    $orderItem->update(['refund_id' => $refund_new->id]);
                }
            }else{
                foreach ($data['tribute_id'] as $partid) {
                    $orderItem = $this->items->find($partid);
                    $orderItem->update(['refund_id' => $refund_new->id]);
                }
            }
            return;
        }
        return 1;
    }

    public function checkBlack()
    {
        $channel = $this->channel->where('id', $this->channel_id)->get();
        $driver = '';
        foreach ($channel as $val) {
            $driver = $val->driver;
        }
        if ($driver == 'wish') {
            $name = trim($this->shipping_lastname . ' ' . $this->shipping_firstname);
            $blacklist = BlacklistModel::where('zipcode', $this->shipping_zipcode)->where('name', $name);
        } elseif($driver == 'aliexpress') {
            $blacklist = BlacklistModel::where('by_id', $this->by_id);
        } else {
            $blacklist = BlacklistModel::where('email', $this->email);
        }
        if ($blacklist->count() > 0) {
            $this->update(['blacklist' => '0']);
            foreach ($blacklist->get() as $value) {
                if ($value->type == 'CONFIRMED' || $value->type == 'SUSPECTED') {
                    return true;
                }
            }
        }
        return false;
    }

    public function createOrder($data)
    {
        $data['ordernum'] = str_replace('.', '', microtime(true));
        $currency = CurrencyModel::where('code', $data['currency']);
        if($currency->count() > 0) {
            foreach($currency->get() as $value) {
                $data['rate'] = $value['rate'];
            }
        }
        $order = $this->create($data);
        foreach ($data['items'] as $orderItem) {
            if ($orderItem['sku']) {
                $item = ItemModel::where('sku', $orderItem['sku'])->first();
                if ($item) {
                    $orderItem['item_id'] = $item->id;
                    $orderItem['item_status'] = $item->status;
                }
            }
            if (!isset($orderItem['item_id'])) {
                $orderItem['item_id'] = 0;
                $order->update(['status' => 'REVIEW']);
                $order->remark($orderItem['channel_sku'] . '找不到对应产品.');
            }
            $order->items()->create($orderItem);
        }
        if($order->status == 'COMPLETE' && $order->fulfill_by == 'AFN') {
            foreach($order->items as $orderItem) {
                ChannelSaleModel::create(['item_id' => $orderItem->item_id,
                                          'channel_sku' => $orderItem->channel_sku,
                                          'quantity' => $orderItem->quantity,
                                          'account_id' => $order->channel_account_id,
                                          'create_time' => $order->create_time]);
            }
        }

        //客户留言需审核
        if ($order->customer_remark != null && $order->customer_remark != '') {
            $order->update(['status' => 'REVIEW']);
        }

        //客户备注需审核
        if (isset($data['remark']) and !empty($data['remark'])) {
            $order->update(['status' => 'REVIEW', 'customer_remark' => $data['remark']]);
        }

        //黑名单需审核
        if ($order->status != 'UNPAID' && $order->checkBlack()) {
            $order->update(['status' => 'REVIEW']);
            $order->remark('黑名单订单.');
        }

        if ($order->status == 'PAID') {
            $order->update(['status' => 'PREPARED']);
        }

        return $order;
    }

    //todo: Update order
    public function updateOrder($data, $order)
    {
        $order = $order->update($data);
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

    public function createVirtualPackage()
    {
        $package = [];
        //channel
        $package['channel_id'] = $this->channel_id ? $this->channel_id : '';
        $package['channel_account_id'] = $this->channel_account_id ? $this->channel_account_id : '';
        //type
        $package['type'] = $this->items->count() > 1 ? 'MULTI' : ($this->items->first()['quantity'] > 1 ? 'SINGLEMULTI' : 'SINGLE');
        $package['weight'] = $this->order_weight;
        $package['email'] = $this->email ? $this->email : '';
        $package['shipping_firstname'] = $this->shipping_firstname ? $this->shipping_firstname : '';
        $package['shipping_lastname'] = $this->shipping_lastname ? $this->shipping_lastname : '';
        $package['shipping_address'] = $this->shipping_address ? $this->shipping_address : '';
        $package['shipping_address1'] = $this->shipping_address1 ? $this->shipping_address1 : '';
        $package['shipping_city'] = $this->shipping_city ? $this->shipping_city : '';
        $package['shipping_state'] = $this->shipping_state ? $this->shipping_state : '';
        $package['shipping_country'] = $this->shipping_country ? $this->shipping_country : '';
        $package['shipping_zipcode'] = $this->shipping_zipcode ? $this->shipping_zipcode : '';
        $package['shipping_phone'] = $this->shipping_phone ? $this->shipping_phone : '';
        $package['status'] = 'NEW';
        $package = $this->packages()->create($package);
        if ($package) {
            foreach ($this->items->toArray() as $packageItem) {
                if (!$packageItem['remark']) {
                    $packageItem['remark'] = 'REMARK';
                }
                $packageItem['order_item_id'] = $packageItem['id'];
                if ($packageItem['is_active']) {
                    $newPackageItem = $package->items()->create($packageItem);
                }
            }
            $package->update(['weight' => $package->total_weight]);
        }

        return $package;
    }

    /**
     * @param array $items
     * @return bool
     */
    public function createPackage()
    {
        if ($this->canPackage()) {
            return $this->createVirtualPackage();
        }
        return false;
    }

    /**
     * 根据单号取订单记录
     * @param $query
     * @param $ordernum
     * @return mixed
     */
    public function scopeOfOrdernum($query, $ordernum)
    {
        return $query->where('ordernum', $ordernum);
    }

    /**
     * 计算利润率并处理
     *
     * @param none
     * @return 利润率 小数
     *
     */
    public function calculateProfitProcess()
    {
        $currency = CurrencyModel::where('code', $this->currency)->first()->rate;
        $orderAmount = $this->amount * $currency;
        $orderCosting = $this->all_item_cost;
        $orderChannelFee = $this->calculateOrderChannelFee();
        $orderRate = ($orderAmount - ($orderCosting + $orderChannelFee + $this->logistics_fee)) / $orderAmount;
//        if ($this->status != 'CANCEL' && $orderRate <= 0) {
//            //利润率为负撤销0
//            $this->OrderCancle();
//        }

        return $orderRate;
    }

    /**
     *  计算平台费
     *
     * @param $order 订单 $orderItems 订单条目
     * @return $sum
     *
     */
    public function calculateOrderChannelFee()
    {
        $sum = 0;
        $orderItems = $this->items;
        $channel = $this->channel;
        $currency = CurrencyModel::where('code', 'RMB')->first()->rate;
        foreach ($orderItems as $orderItem) {
            $buf = $orderItem->item->catalog->channels->where('id', $this->channelAccount->catalog_rates_channel_id)->first();
            if($buf) {
                $buf = $buf->pivot;
                $flat_rate_value = $buf->flat_rate;
                $rate_value = $buf->rate;
                $sum += ($orderItem->price * $orderItem->quantity + ($orderItem->quantity / $this->order_quantity) * $this->logistics_fee) * $rate_value/100 + $flat_rate_value * $currency;
            } else {
                return 0;
            }
        }

        return $sum;
    }

    /**
     * 订单取消
     *
     * @param $order 订单 $orderItems 订单条目
     * @return none
     *
     */
    public function OrderCancle()
    {
        $orderItems = $this->items;
        $this->update(['status' => 'REVIEW']);
        $this->remarks()->create(['remark' => 'profit is less than 0', 'user_id' => request()->user()->id]);
        foreach ($orderItems as $orderItem) {
            $orderItem->update(['is_active' => '0']);
        }
        $packages = $this->packages;
        foreach ($packages as $package) {
            foreach ($package->items as $packageItem) {
                $item = $packageItem->item;
                if(!in_array($package->status, ['NEW', 'WAITASSIGN', 'NEED', 'SHIPPED', 'PACKED'])) {
                    $item->unhold($packageItem->warehouse_position_id, $packageItem->quantity,
                         'CANCEL');
                }
                $packageItem->delete();
            }
            $package->delete();
        }
    }

    public function getStatusTextAttribute()
    {
        return config('order.status.' . $this->status);
    }

    public function getActiveTextAttribute()
    {
        return config('order.active.' . $this->active);
    }

    public function getSendEbayMessageHistoryAttribute(){
        if(!$this->ebayMessageList->isEmpty()){
            return $this->ebayMessageList;
        }else{
            return false;
        }
    }

    public function getOrderReamrksAttribute(){
        $remarks = '';
        if(!$this->remarks->isEmpty()){
            foreach ($this->remarks as $remark){
                $remarks .= empty($remarks) ? $remark->remark : $remark->remark.';';

            }
        }
        return $remarks;
    }

}