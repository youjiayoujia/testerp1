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

use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\CurrencyModel;
use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\product\ImageModel;
use App\Models\UserModel;
use App\Models\ItemModel as productItem;

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
            'items' => ItemModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
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
        if($data['refund_account'] == null) {
            $data['refund'] = '';
            $data['refund_currency'] = '';
            $data['refund_account'] = '';
            $data['refund_amount'] = '';
            $data['refund_time'] = '';
        }
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
     * 跳转编辑页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $arr = [];
        foreach($model->orderItem as $orderItem)
        {
            $arr[] = $orderItem->sku;
        }
        foreach($arr as $key => $value) {
            $obj = productItem::where(['sku' => $value])->first();
            if ($obj) {
                $image = ImageModel::where(['id' => $obj->product->default_image])->first()->src;
                $arr[$key] = $image;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->orderItem,
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'items' => ItemModel::all(),
            'aliases' => $model->channel->channelAccount,
            'arr' => $arr,
            'rows' => $model->items()->count(),
        ];

        return view($this->viewPath . 'edit', $response);
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
        if($data['refund_account'] == null) {
            $data['refund'] = '';
            $data['refund_currency'] = '';
            $data['refund_account'] = '';
            $data['refund_amount'] = '';
            $data['refund_time'] = '';
        }
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
            if(count($data['items']) == count($orderItems)) {
                foreach($orderItems as $key2 => $orderItem) {
                    if($key1 == $key2) {
                        $orderItem->update($item);
                    }
                }
            }else{
                foreach($orderItems as $key2 => $orderItem) {
                    $orderItem->delete($item);
                }
                foreach($data['items'] as $value) {
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
        foreach($model->orderItem as $orderItem)
        {
            $arr[] = $orderItem->sku;
        }
        foreach($arr as $key => $value) {
            $obj = productItem::where(['sku' => $value])->first();
            if ($obj) {
                $image = ImageModel::where(['id' => $obj->product->default_image])->first()->src;
                $arr[$key] = $image;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'orderItems' => $model->orderItem,
            'packages' => $model->package,
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
        foreach ($obj->orderItem as $val) {
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
                $result = ImageModel::where(['id' => $obj->product->default_image])->first()->src;
                return json_encode($result);
            }else{
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
        $buf = channelModel::find($id)->channelAccount;
        return json_encode($buf);
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
            foreach($channels as $channel) {
                $orders[$key]['channel_id'] = $channel['id'];
                $accounts = AccountModel::where(['channel_id' => $orders[$key]['channel_id']])->get();
                foreach($accounts as $account) {
                    $orders[$key]['channel_account_id'] = $account['id'];
                    $orders[$key]['customer_service'] = $account['customer_service_id'];
                    $orders[$key]['operator'] = $account['operator_id'];
                    $orders[$key]['affairer'] = NULL;
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
            $orders[$key]['affair_time'] = NULL;
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
            if(($orders[$key]['amount_shipping'] / $orders[$key]['rate']) < 10) {
                $orders[$key]['shipping'] = 'PACKET';
            }else {
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
            $orders[$key]['refund'] = NULL;
            $orders[$key]['refund_currency'] = NULL;
            $orders[$key]['refund_account'] = NULL;
            $orders[$key]['refund_amount'] = NULL;
            $orders[$key]['refund_time'] = NULL;
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
                for($i=0; $i<$len; $i++) {
                    $str = $arr[$i]['attributes'];
                    $array = explode(";", $str);
                    foreach ($array as $value) {
                        if($value  == '') {
                            break;
                        }else {
                            $arr[$i]['attributes'] = $value;
                            $orders[$key]['items'][$itemKey]['sku'] = $channelOrderItem['sku']."-".substr($arr[$i]['attributes'], 6);
                        }
                    }
                }
            }
            $obj = OrderModel::where(['ordernum' => $channelOrder['ordernum']])->get();
            if(!count($obj)) {
                $this->model->createOrder($orders[$key]);
            }
        }
    }

}