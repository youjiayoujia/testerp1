<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;


/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use Cache;
use App\Models\UserModel;
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\OrderModel;
use App\Models\Order\ItemModel;
use App\Models\Stock\TakingFormModel;
use App\Models\LogisticsModel;

class TestController extends Controller
{
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
        $this->mainIndex = route('stock.index');
        $this->mainTitle = '库存开帐';
        $this->viewPath = 'stock.';
    }

    public function test()
    {
        $logisticses = LogisticsModel::whereIn('id', ['7', '8'])->get();
        var_dump($logisticses->toArray());
        // $a = ['abc' => 'test'];
        // $b = 1;
        // $c = serialize([$a,$b]);
        // $d = unserialize($c);
        // var_dump($d);
    }
    // public function test()
    // {
    //     $order = OrderModel::find(58);
    //     $orderItems = $order->orderItem;
    //     for($i = 0; $i <= 10000; $i++)
    //     {
    //         $tmp_order = OrderModel::create($order->toArray());
    //         foreach($orderItems as $orderItem) 
    //         {
    //             $tmp_order->orderItem()->create($orderItem->toArray());
    //         }
    //     }
    //     var_dump('ok');  
    // }
}