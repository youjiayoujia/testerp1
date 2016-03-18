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
use App\Models\Order\ItemModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\ItemModel as productItem;
use DB;

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
        ];

        return view($this->viewPath.'create', $response);
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
        $len = count(array_keys(request()->input('arr.sku')));
        $buf = request()->all();
        $obj = $this->model->create($buf);
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];
            }
            $buf['item_id'] = productItem::where('sku', $buf['sku'])->first()->id;
            $buf['order_id'] = $obj->id;
            ItemModel::create($buf);
        }

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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->orderItem,
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'aliases' => $model->channel->channelAccount,
        ];

        return view($this->viewPath.'edit', $response);
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
        $len = count(array_keys(request()->input('arr.sku')));
        $buf = request()->all();
        $obj = $this->model->find($id)->orderItem;
        $obj_len = count($obj);
        $this->model->find($id)->update($buf);
        for($i=0; $i<$len; $i++)
        {
            unset($buf);
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];
            }
            $buf['order_id'] = $id;
            $buf['item_id'] = productItem::where('sku', $buf['sku'])->first()->id;
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'orderItems' => $model->orderItem,
            'model' => $model,
        ];

        return view($this->viewPath.'show', $response);
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
        foreach($obj->orderItem as $val)
            $val->delete();
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
        if(request()->ajax()) {
            $sku = request()->input('sku');
            $obj = productItem::where(['sku'=>$sku])->get();
            if(count($obj)) {
                return json_encode('sku');
            }
        }

        return json_encode('false');
    }

    /**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxOrderAdd()
    {
        if(request()->ajax()) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath.'add', $response);
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
        $url = 'http://www.choies.com/api/order_date_list?date='.$date;
        $queryServer = curl_init();
        curl_setopt($queryServer, CURLOPT_URL, $url);
        curl_setopt($queryServer, CURLOPT_HEADER, 0);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($queryServer, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($queryServer, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($queryServer);
        curl_close($queryServer);
        $arr = json_decode($data, true);
        $len = count($arr);

//        $arr2 = array();
//        foreach($arr as $key => $val) {
//            count($val['orderitems']);
//            $arr2[] = $val['orderitems'];
//        }

        for($i=0; $i<$len; $i++) {
            $id = $arr[$i]['id'];
            $status = $arr[$i]['payment_status'];
            $date_purchased = $arr[$i]['date_purchased'];
            $ia_active = $arr[$i]['is_active'];
            $ordernum = $arr[$i]['ordernum'];
            $email = $arr[$i]['email'];
            $customer_id = $arr[$i]['customer_id'];
            $currency = $arr[$i]['currency'];
            $rate = $arr[$i]['rate'];
            $amount = $arr[$i]['amount'];
            $amount_product = $arr[$i]['amount_products'];
            $amount_shipping = $arr[$i]['amount_shipping'];
            $amount_coupon = $arr[$i]['amount_coupon'];
            $amount_payment = $arr[$i]['amount_payment'];
            $order_insurance = $arr[$i]['order_insurance'];
            $ip = $arr[$i]['ip_address'];
            $remark = $arr[$i]['remark'];
            $payment = $arr[$i]['payment'];
            $payment_date = $arr[$i]['payment_date'];
            $order_from = $arr[$i]['order_from'];
            $shipping_firstname = $arr[$i]['shipping_firstname'];
            $shipping_lastname = $arr[$i]['shipping_lastname'];
            $shipping_address = $arr[$i]['shipping_address'];
            $shipping_city = $arr[$i]['shipping_city'];
            $shipping_state = $arr[$i]['shipping_state'];
            $shipping_country = $arr[$i]['shipping_country'];
            $shipping_zipcode = $arr[$i]['shipping_zip'];
            $shipping_phone = $arr[$i]['shipping_phone'];
            $billing_firstname = $arr[$i]['billing_firstname'];
            $billing_lastname = $arr[$i]['billing_lastname'];
            $billing_address = $arr[$i]['billing_address'];
            $billing_city = $arr[$i]['billing_city'];
            $billing_state = $arr[$i]['billing_state'];
            $billing_country = $arr[$i]['billing_country'];
            $billing_zipcode = $arr[$i]['billing_zip'];
            $billing_phone = $arr[$i]['billing_phone'];
            DB::insert('insert into orders (id, email) values (?, ?)', [$id, $email]);
        }
        echo "<pre>";var_dump($arr);echo "</pre>";exit;
//        echo "<pre>";var_dump($arr2);echo "</pre>";exit;
    }

//    public function account()
//    {
//        $id = 1;
//        $buf = $this->model->find($id)->channel->channelAccount;
//        echo "<pre>";
//        var_dump($buf->toArray());
//        echo "</pre>";
//        exit;
//        return json_encode($buf);
//    }

}