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
use App\Models\OrderModel;

class OrderController extends Controller
{
    public function __construct(OrderModel $order)
    {
        $this->model = $order;
        $this->mainIndex = route('order.index');
        $this->mainTitle = '订单管理';
        $this->viewPath = 'order.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
        ];
        return view($this->viewPath.'create', $response);
    }

}