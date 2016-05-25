<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;

use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;

class TestController extends Controller
{
    public function __construct(OrderModel $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function index()
    {
        $account = AccountModel::findOrFail(1);
        $startDate = '2016-05-18 00:00:00';
        $endDate = date('Y-m-d 00:00:00', time());
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->drive, $account->api_config);
        $orderId = '104-9055646-5459426';
        $order = $channel->getOrder($orderId);
        Tool::show($order);
        $orderList = $channel->listOrders($startDate, $endDate, $status, 20);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            if ($thisOrder) {
                $thisOrder->updateOrder($order);
            } else {
                $order['channel_id'] = $account->channel->id;
                $order['channel_account_id'] = $account->id;
                $this->orderModel->createOrder($order);
            }
        }
    }
}