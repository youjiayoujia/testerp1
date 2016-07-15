<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:46
 */
namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderMarkLogicModel;
use App\Models\ChannelModel;

class OrderMarkLogicController extends Controller
{
    public function __construct(OrderMarkLogicModel $orderMarkLogic)
    {
        $this->model = $orderMarkLogic;
        $this->mainIndex = route('orderMarkLogic.index');
        $this->mainTitle = '标记发货规则';
        $this->viewPath = 'order.orderMarkLogic.';
    }

    public function create()
    {

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'order_status'=>config('order.status')
        ];
        return view($this->viewPath . 'create', $response);
    }

}

