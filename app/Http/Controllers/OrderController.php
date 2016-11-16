<?php
/**
 * 订单控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/19
 * Time: 上午9:53
 */

namespace App\Http\Controllers;

use App\Jobs\Job;
use App\Jobs\DoPackages;
use App\Jobs\AssignStocks;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\CountriesModel;
use App\Models\CurrencyModel;
use App\Models\ItemModel;
use App\Models\Order\RemarkModel;
use App\Models\OrderModel;
use App\Models\product\ImageModel;
use App\Models\UserModel;
use App\Models\ItemModel as productItem;
use App\Models\Order\ItemModel as orderItem;

class OrderController extends Controller
{
    public function __construct(OrderModel $order)
    {
        $this->model = $order;
        $this->mainIndex = route('order.index');
        $this->mainTitle = '订单管理';
        $this->viewPath = 'order.';
    }

    /**
     * 跳转创建页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 获取国家信息
     */
    public function ajaxCountry()
    {
        if (request()->ajax()) {
            $country = trim(request()->input('shipping_country'));
            $buf = CountriesModel::where('code', 'like', '%' . $country . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->code;
                $arr[$key]['text'] = $value->code;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    /**
     * 获取sku信息
     */
    public function ajaxSku()
    {
        if (request()->ajax()) {
            $sku = trim(request()->input('sku'));
            $buf = ItemModel::where('sku', 'like', '%' . $sku . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->sku;
                $arr[$key]['text'] = $value->warehouse->name . ' ' . $value->sku . ' ' .
                    $value->product->c_name . ' ' . $value->getAllQuantityAttribute() . ' ' . $value->status_name;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    public function createVirtualPackage()
    {
        $model = $this->model->where('status', 'PREPARED')->get();
        foreach($model as $key => $single) {
            $job = new DoPackages($single);
            $job = $job->onQueue('doPackages');
            $this->dispatch($job);
        }

        return redirect('/')->with('alert', $this->alert('success', '已成功加入doPackages队列'));
    }

    /**
     * 保存数据
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rule(request()));
        $data = request()->all();
        foreach ($data['arr'] as $key => $item) {
            foreach ($item as $k => $v) {
                $data['items'][$k][$key] = $v;
            }
        }
        unset($data['arr']);
        $data['priority'] = 0;
        $data['package_times'] = 0;
        $model = $this->model->createOrder($data);
        $model = $this->model->with('items')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));

        return redirect($this->mainIndex);
    }

    /**
     * 首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $sx = request()->input('sx');
        $lr = request()->input('lr');
        $special = request()->input('special');
        if ($sx != null && $lr != '') {
            if ($sx == 'high') {
                $order = $this->model->where('profit_rate', '>=', $lr);
            }else {
                $order = $this->model->where('profit_rate', '<=', $lr);
            }
        } else {
            $order = $this->model;
        }
        if ($special == 'yes') {
            $order = $this->model->where('customer_remark', '!=', '');
        }
        $subtotal = 0;
        foreach($this->autoList($this->model) as $value) {
            $subtotal += $value->amount * $value->rate;
        }
        $rmbRate = CurrencyModel::where('code', 'RMB')->first()->rate;
        //订单首页不显示数据
        $url = 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $orderUrl = route('order.index');
        if ($url == $orderUrl) {
            $order = $this->model->where('id', 0);
            $subtotal = 0;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($order),
            'mixedSearchFields' => $this->model->mixed_search,
            'countries' => CountriesModel::all(),
            'currencys' => CurrencyModel::all(),
            'subtotal' => $subtotal,
            'rmbRate' => $rmbRate,
        ];
        return view($this->viewPath . 'index', $response);
    }

    //订单统计
    public function orderStatistics()
    {
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $orders = $this->model->where('create_time', '<=', $endDate)->where('create_time', '>=', $startDate);
        $data['totalAmount'] = '';
        $data['averageProfit'] = '';
        $data['totalPlatform'] = '';
        $profitAmount = '';
        if($orders->count()) {
            foreach($orders->get() as $order) {
                $data['totalAmount'] += $order->amount * $order->rate;
                $profitAmount += $order->calculateProfitProcess() * $order->amount * $order->rate;
                $data['totalPlatform'] += $order->calculateOrderChannelFee();
            }
            $data['averageProfit'] = $profitAmount / $data['totalAmount'];
        }

        return $data;
    }

    public function invoice($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'germanInvoice', $response);
    }

    /**
     * 跳转编辑页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->items,
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'aliases' => $model->channel->accounts,
            'arr' => $arr,
            'rows' => $model->items()->count(),
            'countries' => CountriesModel::all(),
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 跳转退款页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refund($id)
    {
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->items->where('is_refund', '0'),
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'aliases' => $model->channel->accounts,
            'arr' => $arr,
            'rows' => $model->items()->count(),
        ];

        return view($this->viewPath . 'refund', $response);
    }

    /**
     * 保存退款信息
     *
     * @param $id
     */
    public function refundUpdate($id)
    {
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $data = request()->all();
        $data['order_id']   = $id;
        $data['channel_id'] = $model->channel_id;
        $data['account_id'] = $model->channel_account_id;
        $model->refundCreate($data, request()->file('image'));
        $to = json_encode($model);
        $this->eventLog($userName->name, '退款新增,id='.$id, $to, $from);
        return redirect($this->mainIndex);
    }

    /**
     * 更新备注
     */
    public function remarkUpdate($id)
    {
        request()->flash();
        $data = request()->all();
        $data['user_id'] = request()->user()->id;
        $this->model->find($id)->remarks()->create($data);
        return redirect($this->mainIndex);
    }

    /**
     * 数据更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        request()->flash();
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->with('items')->find($id));
        $this->validate(request(), $this->model->updateRule(request()));
        $data = request()->all();
        $data['status'] = 'REVIEW';
        foreach ($data['arr'] as $key => $item) {
            foreach ($item as $k => $v) {
                $data['items'][$k][$key] = $v;
            }
        }
        unset($data['arr']);
        $this->model->find($id)->update($data);
        foreach ($data['items'] as $key1 => $item) {
            $obj = productItem::where('sku', $item['sku'])->get();
            if (!count($obj)) {
                $item['item_id'] = 0;
                $this->model->find($id)->update(['status' => 'ERROR']);
            } else {
                $item['item_id'] = productItem::where('sku', $item['sku'])->first()->id;
                $item['item_status'] = productItem::where('sku', $item['sku'])->first()->status;
            }
            $orderItems = $this->model->find($id)->items;
            if (count($data['items']) == count($orderItems)) {
                foreach ($orderItems as $key2 => $orderItem) {
                    if ($key1 == $key2) {
                        $orderItem->update($item);
                    }
                }
            } else {
                foreach ($orderItems as $key2 => $orderItem) {
                    $orderItem->delete($item);
                }
                foreach ($data['items'] as $value) {
                    $value['item_id'] = productItem::where('sku', $value['sku'])->first()->id;
                    $value['item_status'] = productItem::where('sku', $value['sku'])->first()->status;
                    $this->model->find($id)->items()->create($value);
                }
            }
        }
        if($this->model->find($id)->packages) {
            foreach($this->model->find($id)->packages as $package) {
                foreach($package->items as $item) {
                    $item->delete();
                }
                $package->delete();
            }
        }
        $job = new DoPackages($this->model->find($id));
        $job->onQueue('doPackages');
        $this->dispatch($job);

        $to = json_encode($this->model->with('items')->find($id));
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);

        return redirect($this->mainIndex);
    }

    /**
     * 信息详情页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'orderItems' => $model->items,
            'packages' => $model->packages,
            'model' => $model,
            'arr' => $arr,
        ];

        return view($this->viewPath . 'show', $response);
    }

    /**
     * 数据删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $obj = $this->model->find($id);
        foreach ($obj->items as $val) {
            $val->delete();
        }
        $obj->delete($id);

        return redirect($this->mainIndex);
    }

    /**
     * 验证订单sku
     *
     * @return string
     */
    public function getMsg()
    {
        if (request()->ajax()) {
            $sku = request()->input('sku');
            $obj = productItem::where(['sku' => $sku])->first();
            if ($obj) {
                $result = $obj->product->url1;
                return json_encode($result);
            } else {
                return json_encode(false);
            }

        }
        return json_encode(false);
    }

    /**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxOrderAdd()
    {
        if (request()->ajax()) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath . 'add', $response);
        }
        return null;
    }

    /**
     * 渠道对应渠道账号
     *
     * @return string
     */
    public function account()
    {
        $id = request()->input('id');
        $buf = ChannelModel::find($id)->accounts;
        return json_encode($buf);
    }

    //审核
    public function updateStatus()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $model = $this->model->find($order_id);
        $model->update(['status' => 'PREPARED']);
//        $job = new DoPackages($model);
//        $job->onQueue('doPackages');
//        $this->dispatch($job);
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '审核更新,id='.$order_id, $to, $from);

        return 1;
    }

    //暂停发货
    public function updatePrepared()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $this->model->find($order_id)->update(['active' => 'STOP']);
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '暂停发货更新,id='.$order_id, $to, $from);

        return 1;
    }

    //恢复正常
    public function updateNormal()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = base64_encode(serialize($this->model->find($order_id)));
        $this->model->find($order_id)->update(['active' => 'NORMAL']);
        $to = base64_encode(serialize($this->model->find($order_id)));
        $this->eventLog($userName->name, '恢复正常更新,id='.$order_id, $to, $from);

        return 1;
    }

    //恢复订单
    public function updateRecover()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = base64_encode(serialize($this->model->find($order_id)));
        $this->model->find($order_id)->update(['status' => 'REVIEW']);
        $to = base64_encode(serialize($this->model->find($order_id)));
        $this->eventLog($userName->name, '恢复订单更新,id='.$order_id, $to, $from);

        return 1;
    }

    /**
     * 批量撤单
     *
     * @return int
     */
    public function withdrawAll()
    {
        $userName = UserModel::find(request()->user()->id);
        $order_ids = request()->input('order_ids');
        $order_ids_arr = explode(',', $order_ids);
        $data = request()->all();
        foreach($order_ids_arr as $id) {
            if($this->model->find($id)) {
                $from = json_encode($this->model->find($id));
                $this->model->find($id)->update(['status' => 'CANCEL', 'withdraw_reason' => $data['withdraw_reason'], 'withdraw' => $data['withdraw']]);
                $to = json_encode($this->model->find($id));
                $this->eventLog($userName->name, '批量撤单新增,id='.$id, $to, $from);
            }
            if($this->model->find($id)->packages) {
                foreach($this->model->find($id)->packages as $package) {
                    foreach($package->items as $item) {
                        $item->delete();
                    }
                    $package->delete();
                }
            }
        }
        return 1;
    }

    public function withdrawUpdate($id)
    {
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($id));
        request()->flash();
        $data = request()->all();
        $this->model->find($id)->update(['status' => 'CANCEL', 'withdraw_reason' => $data['withdraw_reason'], 'withdraw' => $data['withdraw']]);
        if($this->model->find($id)->packages->count()) {
            foreach($this->model->find($id)->packages as $package) {
                foreach($package->items as $item) {
                    $item->delete();
                }
                $package->delete();
            }
        }
        $to = json_encode($this->model->find($id));
        $this->eventLog($userName->name, '撤单新增,id='.$id, $to, $from);

        return redirect($this->mainIndex);
    }

    public function withdraw($id)
    {
        $model = $this->model->find($id);

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];

        return view($this->viewPath . 'withdraw', $response);
    }

    /**
     * 获取choies订单数据
     *
     */
    public function getChoiesOrder()
    {
        $date = date('Y-m-d');
        $url = 'http://www.choies.com/api/order_date_list?date=' . $date;
        $queryServer = curl_init();
        curl_setopt($queryServer, CURLOPT_URL, $url);
        curl_setopt($queryServer, CURLOPT_HEADER, 0);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($queryServer, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($queryServer, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($queryServer);
        curl_close($queryServer);
        $channelOrders = json_decode($data, true);
        $orders = [];
        foreach ($channelOrders as $key => $channelOrder) {
            $name = substr($url, 11, 6);
            $channels = ChannelModel::where(['name' => $name])->get();
            foreach ($channels as $channel) {
                $orders[$key]['channel_id'] = $channel['id'];
                $accounts = AccountModel::where(['channel_id' => $orders[$key]['channel_id']])->get();
                foreach ($accounts as $account) {
                    $orders[$key]['channel_account_id'] = $account['id'];
                    $orders[$key]['customer_service'] = $account['customer_service_id'];
                    $orders[$key]['operator'] = $account['operator_id'];
                    $orders[$key]['affairer'] = null;
                }
            }
            $orders[$key]['ordernum'] = $channelOrder['ordernum'];
            $orders[$key]['channel_ordernum'] = $channelOrder['ordernum'];
            $orders[$key]['email'] = $channelOrder['email'];
            $orders[$key]['status'] = 'PAID';
            $orders[$key]['active'] = 'NORMAL';
            $orders[$key]['ip'] = $channelOrder['ip_address'];
            $orders[$key]['address_confirm'] = 1;
            $orders[$key]['remark'] = $channelOrder['remark'];
            if ($orders[$key]['remark'] != null && $orders[$key]['remark'] != '') {
                $orders[$key]['status'] = 'REVIEW';
            }
            $orders[$key]['affair_time'] = null;
            $orders[$key]['create_time'] = $channelOrder['date_purchased'];
            $orders[$key]['is_partial'] = 0;
            $orders[$key]['by_hand'] = 0;
            $orders[$key]['is_affair'] = 0;
            $orders[$key]['currency'] = $channelOrder['currency'];
            $orders[$key]['rate'] = $channelOrder['rate'];
            $orders[$key]['amount'] = $channelOrder['amount'];
            $orders[$key]['amount_product'] = $channelOrder['amount_products'];
            $orders[$key]['amount_coupon'] = $channelOrder['order_insurance'];
            $orders[$key]['amount_shipping'] = $channelOrder['amount_shipping'] + $orders[$key]['amount_coupon'];
            if (($orders[$key]['amount_shipping'] / $orders[$key]['rate']) < 10) {
                $orders[$key]['shipping'] = 'PACKET';
            } else {
                $orders[$key]['shipping'] = 'EXPRESS';
            }
            $orders[$key]['shipping_firstname'] = $channelOrder['shipping_firstname'];
            $orders[$key]['shipping_lastname'] = $channelOrder['shipping_lastname'];
            $orders[$key]['shipping_address'] = $channelOrder['shipping_address'];
            $orders[$key]['shipping_city'] = $channelOrder['shipping_city'];
            $orders[$key]['shipping_state'] = $channelOrder['shipping_state'];
            $orders[$key]['shipping_country'] = $channelOrder['shipping_country'];
            $orders[$key]['shipping_zipcode'] = $channelOrder['shipping_zip'];
            $orders[$key]['shipping_phone'] = $channelOrder['shipping_phone'];
            $orders[$key]['payment'] = $channelOrder['payment'];
            $orders[$key]['billing_firstname'] = $channelOrder['billing_firstname'];
            $orders[$key]['billing_lastname'] = $channelOrder['billing_lastname'];
            $orders[$key]['billing_address'] = $channelOrder['billing_address'];
            $orders[$key]['billing_city'] = $channelOrder['billing_city'];
            $orders[$key]['billing_state'] = $channelOrder['billing_state'];
            $orders[$key]['billing_country'] = $channelOrder['billing_country'];
            $orders[$key]['billing_zipcode'] = $channelOrder['billing_zip'];
            $orders[$key]['billing_phone'] = $channelOrder['billing_phone'];
            $orders[$key]['payment_date'] = $channelOrder['payment_date'];
            $orders[$key]['transaction_number'] = $channelOrder['trans_id'];
            $orders[$key]['cele_admin'] = $channelOrder['cele_admin'];
            $orders[$key]['priority'] = 0;
            $orders[$key]['package_times'] = 0;
            foreach ($channelOrder['orderitems'] as $itemKey => $channelOrderItem) {
                $orders[$key]['items'][$itemKey]['item_id'] = 0;
                $orders[$key]['items'][$itemKey]['quantity'] = $channelOrderItem['quantity'];
                $orders[$key]['items'][$itemKey]['price'] = $channelOrderItem['price'];
                $orders[$key]['items'][$itemKey]['is_active'] = 1;
                $orders[$key]['items'][$itemKey]['status'] = 'NEW';
                $orders[$key]['items'][$itemKey]['is_gift'] = $channelOrderItem['is_gift'];
                $arr = $channelOrder['orderitems'];
                $len = count($arr);
                for ($i = 0; $i < $len; $i++) {
                    $str = $arr[$i]['attributes'];
                    $array = explode(";", $str);
                    foreach ($array as $value) {
                        if ($value == '') {
                            break;
                        } else {
                            $arr[$i]['attributes'] = $value;
                            $orders[$key]['items'][$itemKey]['sku'] = $channelOrderItem['sku'] . "-" . substr($arr[$i]['attributes'],
                                    6);
                        }
                    }
                }
            }
            $obj = OrderModel::where(['ordernum' => $channelOrder['ordernum']])->get();
            if (!count($obj)) {
                $this->model->createOrder($orders[$key]);
            }
        }
    }

}