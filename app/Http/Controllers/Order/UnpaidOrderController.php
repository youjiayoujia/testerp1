<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/9/27
 * Time: 上午9:59
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Order\UnpaidOrderModel;
use App\Models\UserModel;

class UnpaidOrderController extends Controller
{
    public function __construct(UnpaidOrderModel $unpaidOrder)
    {
        $this->model = $unpaidOrder;
        $this->mainIndex = route('unpaidOrder.index');
        $this->mainTitle = '未付款订单';
        $this->viewPath = 'order.unpaidOrder.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'users' => UserModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels' => ChannelModel::all(),
            'users' => UserModel::all(),
        ];

        return view($this->viewPath . 'edit', $response);
    }
}