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
use App\Jobs\DoPackage;
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
                $arr[$key]['text'] = $value->sku;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    public function putNeedQueue()
    {
        $len = 1000;
        $start = 0;
        $orders = $this->model->where(['status' => 'NEED'])->skip($start)->take($len)->get();
        while ($orders->count()) {
            foreach ($orders as $order) {
                $job = new DoPackage($order);
                $job->onQueue('doPackages');
                $this->dispatch($job);
            }
            $start += $len;
            $orders = $this->model->where(['status' => 'NEED'])->skip($start)->take($len)->get();
        }
        return redirect(route('dashboard.index'))->with('alert', $this->alert('success', '添加至[DO PACKAGE]队列成功'));
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
        $this->model->createOrder($data);

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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'countries' => CountriesModel::all(),
        ];
        return view($this->viewPath . 'index', $response);
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
            'orderItems' => $model->items,
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
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['order_id'] = $id;
        $model->refundCreate($data, request()->file('image'));
        return redirect($this->mainIndex);
    }

    /**
     * 部分退款
     */
    public function refundAll()
    {
        $ids = request()->input('ids');
        $id_arr = explode(',', $ids);
        if (!empty($id_arr)) {
            foreach ($id_arr as $id) {
                $model = orderItem::find($id);
                $model->update(['is_refund' => 1]);
            }
        }
        return 1;
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
        $this->validate(request(), $this->model->rule(request()));
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
                    $this->model->find($id)->items()->create($value);
                }
            }
        }

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
        $this->model->find($order_id)->update(['status' => 'PREPARED']);

        return 1;
    }

    //暂停发货
    public function updatePrepared()
    {
        $order_id = request()->input('order_id');
        $this->model->find($order_id)->update(['active' => 'STOP']);

        return 1;
    }

    //恢复正常
    public function updateNormal()
    {
        $order_id = request()->input('order_id');
        $this->model->find($order_id)->update(['active' => 'NORMAL']);

        return 1;
    }

    public function withdrawUpdate($id)
    {
        request()->flash();
        $data = request()->all();
        $this->model->find($id)->update(['status' => 'CANCEL', 'withdraw' => $data['withdraw']]);

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