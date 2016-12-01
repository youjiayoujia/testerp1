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

use Queue;
use App\Jobs\AssignStocks;
use App\Jobs\AssignLogistics;
use App\Jobs\PlaceLogistics;
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
        'is_review',
        'active',
        'order_is_alert',
        'amount',
        'gross_margin',
        'profit',
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
    private $canCancelStatus = ['SHIPPED', 'COMPLETE'];

    public $searchFields = ['ordernum' => '订单号', 'channel_ordernum' => '渠道订单号', 'email' => '邮箱', 'by_id' => '买家ID'];

    //退款rules
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

    //添加rules
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
            'amount_coupon' => 'required',
            'shipping_firstname' => 'required',
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

    //更新rules
    public function updateRule($request)
    {
        $arr = [
            'shipping_firstname' => 'required',
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

    //未付款订单
    public function unpaidOrder()
    {
        return $this->belongsTo('App\Models\Order\UnpaidOrderModel', 'by_id', 'ordernum');
    }

    //订单产品
    public function items()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'order_id', 'id');
    }

    //订单包裹
    public function packages()
    {
        return $this->hasMany('App\Models\PackageModel', 'order_id', 'id');
    }

    //订单渠道
    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    //订单渠道账号
    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id', 'id');
    }

    //订单国家
    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
    }

    //订单币种
    public function currency()
    {
        return $this->belongsTo('App\Models\CurrencyModel', 'currency', 'code');
    }

    //运营人员
    public function userAffairer()
    {
        return $this->belongsTo('App\Models\UserModel', 'affairer', 'id');
    }

    //客服人员
    public function userService()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service', 'id');
    }

    //运营人员
    public function userOperator()
    {
        return $this->belongsTo('App\Models\UserModel', 'operator', 'id');
    }

    //订单备注
    public function remarks()
    {
        return $this->hasMany('App\Models\Order\RemarkModel', 'order_id', 'id');
    }

    //退款记录
    public function refunds()
    {
        return $this->hasMany('App\Models\Order\RefundModel', 'order_id', 'id');
    }

    //订单需求
    public function requires()
    {
        return $this->hasMany('App\Models\RequireModel', 'order_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message\MessageModel', 'channel_order_number', 'channel_ordernum');
    }

    //ebay消息记录
    public function ebayMessageList()
    {
        return $this->hasMany('App\Models\Message\SendEbayMessageListModel', 'order_id', 'id');
    }

    //订单重量
    public function getOrderWeightAttribute()
    {
        $items = $this->items;
        $weight = 0;
        foreach ($items as $item) {
            $weight += $item->item->weight * $item->quantity;
        }

        return $weight;
    }

    //多重查询
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
            ],
            'filterSelects' => [
                'status' => config('order.status'),
                'active' => config('order.active'),
                'is_chinese' => config('order.is_chinese')
            ],
            'sectionSelect' => [
                'price' => ['amount', 'profit', 'profit_rate'],
                'time' => ['created_at'],
            ],
            'relatedSearchFields' => [
                'country' => ['code'],
                'items' => ['sku'],
                'channelAccount' => ['alias'],
                'userService' => ['name'],
                'packages' => ['tracking_no'],
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => $arr],
                'items' => ['item_status' => config('item.status')],
                'remarks' => ['type' => config('order.review_type')],
                'packages' => ['is_mark' => config('order.is_mark')],
            ],
            'doubleRelatedSearchFields' => [
                'packages' => ['logistics' => ['code']],
            ],
        ];
    }

    //状态名称
    public function getStatusNameAttribute()
    {
        $config = config('order.status');
        return isset($config[$this->status]) ? $config[$this->status] : '';
    }

    //状态颜色
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

    //激活名称
    public function getActiveNameAttribute()
    {
        $arr = config('order.active');
        return $arr[$this->active];
    }

    //是否部分发货
    public function getIsPartialNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_partial];
    }

    //是否手工发货
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

    //地址是否验证
    public function getAddressConfirmNameAttribute()
    {
        $arr = config('order.address');
        return $arr[$this->address_confirm];
    }

    //撤销原因
    public function getWithdrawNameAttribute()
    {
        $arr = config('order.withdraw');
        return $arr[$this->withdraw];
    }

    //物流方式
    public function getLogisticsAttribute()
    {
        $logistics = '';
        foreach ($this->packages as $package) {
            $logisticsName = $package->logistics ? $package->logistics->code : '';
            $logistics .= $logisticsName . ' ';
        }

        return $logistics;
    }

    //追踪号
    public function getCodeAttribute()
    {
        $code = '';
        foreach ($this->packages as $package) {
            $trackingNo = $package->tracking_no;
            $code .= $trackingNo . ' ';
        }

        return $code;
    }

    //订单成本
    public function getAllItemCostAttribute()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->item->purchase_price * $item->quantity;
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

    //订单产品数量
    public function getOrderQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    //物流成本
    public function getLogisticsFeeAttribute()
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total += $package->calculateLogisticsFee();
        }
        return $total;
    }

    public function packagesToQueue()
    {
        foreach ($this->packages as $package) {
            switch ($package->status) {
                case 'NEW':
                    $job = new AssignStocks($package);
                    Queue::pushOn('assignStocks', $job);
                    break;
                case 'WAITASSIGN':
                    $job = new AssignLogistics($package);
                    Queue::pushOn('assignLogistics', $job);
                    break;
                case 'ASSIGNED':
                    $job = new PlaceLogistics($package);
                    Queue::pushOn('placeLogistics', $job);
                    break;
                case 'NEED':
                    $job = new AssignStocks($package);
                    Queue::pushOn('assignStocks', $job);
                    break;
            }
        }
    }

    //订单可用状态
    public function getActiveItemsAttribute()
    {
        return $this->items->where('is_active', '1');
    }

    //订单状态
    public function getStatusTextAttribute()
    {
        return config('order.status.' . $this->status);
    }

    //售后状态
    public function getActiveTextAttribute()
    {
        return config('order.active.' . $this->active);
    }

    //ebay订单历史
    public function getSendEbayMessageHistoryAttribute()
    {
        if (!$this->ebayMessageList->isEmpty()) {
            return $this->ebayMessageList;
        } else {
            return false;
        }
    }

    //订单备注
    public function getOrderReamrksAttribute()
    {
        $remarks = '';
        if (!$this->remarks->isEmpty()) {
            foreach ($this->remarks as $remark) {
                $remarks .= empty($remarks) ? $remark->remark : $remark->remark . ';';

            }
        }
        return $remarks;
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

    //退款
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
            $refund_new = $refund->create($data);
            if ($data['type'] == 'FULL') {
                foreach ($data['arr']['id'] as $fullid) {
                    $orderItem = $this->items->find($fullid);
                    $orderItem->update(['refund_id' => $refund_new->id]);
                }
            } else {
                foreach ($data['tribute_id'] as $partid) {
                    $orderItem = $this->items->find($partid);
                    $orderItem->update(['refund_id' => $refund_new->id]);
                }
            }
            return;
        }
        return 1;
    }

    //创建订单
    public function createOrder($data)
    {
        $data['ordernum'] = str_replace('.', '', microtime(true));
        $currency = CurrencyModel::where('code', $data['currency'])->first();
        if ($currency) {
            $data['rate'] = $currency->rate;
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
                $order->update(['status' => 'REVIEW',]);
                $order->remark($orderItem['channel_sku'] . '找不到对应产品.', 'ITEM');
            }
            $order->items()->create($orderItem);
        }

        if ($order->status == 'PAID') {
            $order->update(['status' => 'PREPARED']);
        }

        return $order;
    }

    //更新订单
    public function updateOrder($data, $order)
    {
        $order = $order->update($data);
        return $order;
    }

    //添加订单备注
    public function remark($remark, $type = 'DEFAULT', $user_id = 0)
    {
        return $this->remarks()->create(['type' => $type, 'remark' => $remark, 'user_id' => $user_id]);
    }

    //判断是否可打包
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

    //创建包裹
    public function createPackage()
    {
        if ($this->canPackage()) {
            return $this->createVirtualPackage();
        }
        return false;
    }

    //创建虚拟包裹
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

    //计算利润率
    public function calculateProfitProcess()
    {
        $rate = CurrencyModel::where('code', $this->currency)->first()->rate;
        $rmbRate = CurrencyModel::where('code', 'RMB')->first()->rate;
        $orderAmount = ($this->amount + $this->amount_shipping) * $rate;
        $itemCost = $this->all_item_cost * $rmbRate;
        $logisticsCost = $this->logistics_fee * $rmbRate;
        $orderChannelFee = $this->calculateOrderChannelFee();
        $orderProfit = round($orderAmount - $itemCost - $logisticsCost - $orderChannelFee, 4);
        $orderProfitRate = $orderProfit / $orderAmount;
        $this->update(['profit' => $orderProfit, 'profit_rate' => $orderProfitRate]);
        return $orderProfitRate;
    }

    //计算平台费
    public function calculateOrderChannelFee()
    {
        $sum = 0;
        $orderItems = $this->items;
        $channel = $this->channel;
        $currency = CurrencyModel::where('code', 'RMB')->first()->rate;
        foreach ($orderItems as $orderItem) {
            $buf = $orderItem->item->catalog->channels->where('id',
                $this->channelAccount->catalog_rates_channel_id)->first();
            if ($buf) {
                $buf = $buf->pivot;
                $flat_rate_value = $buf->flat_rate;
                $rate_value = $buf->rate;
                $sum += ($orderItem->price * $orderItem->quantity + ($orderItem->quantity / $this->order_quantity) * $this->logistics_fee) * $rate_value / 100 + $flat_rate_value * $currency;
            } else {
                return 0;
            }
        }

        return $sum;
    }

    //黑名单验证
    public function checkBlack()
    {
        $channel = $this->channel->find($this->channel_id);
        $count = 0;
        $blackList = BlacklistModel::whereIN('type', ['CONFIRMED', 'SUSPECTED']);
        if ($channel) {
            switch ($channel->driver) {
                case 'wish':
                    $name = trim($this->shipping_lastname . ' ' . $this->shipping_firstname);
                    $count = $blackList->where('zipcode', $this->shipping_zipcode)
                        ->where('name', $name)->count();
                    break;
                case 'aliexpress':
                    if ($this->by_id) {
                        $count = $blackList->where('by_id', $this->by_id)->count();
                    }
                    break;
                default:
                    if ($this->email) {
                        $count = $blackList->where('email', $this->email)->count();;
                    }
                    break;
            }
            if ($count > 0) {
                $this->update(['blacklist' => '0']);
                return true;
            }
        }
        return false;
    }

    /**
     * 订单撤销
     *
     * @return boolean
     */
    public function cancelOrder($type, $reason = '')
    {
        if ($this->status != 'CANCEL') {
            if (!in_array($this->status, $this->canCancelStatus)) {
                //取消包裹
                foreach ($this->packages as $package) {
                    $package->cancelPackage();
                }
                //撤销订单
                $this->update([
                    'status' => 'CANCEL',
                    'withdraw' => $type,
                    'withdraw_reason' => $reason
                ]);
            } else {
                return false;
            }
        }
        return true;
    }
}