<?php
/**
 * 汇率控制器
 * 处理汇率相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyModel;
use App\Models\PackageModel;
use App\Models\PickListModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\Stock\AdjustmentModel;
use App\Models\OrderModel;
use App\Models\ItemModel;

class TestController extends Controller
{
    public function test()
    {
        $order = OrderModel::find(4);
        $orderItems = $order->items;
        $arr = $order->orderNeedArray();
        echo "<pre>";
        var_dump($order->orderStockDiff($arr));
        print_r($order->explodeOrder());
        echo "</pre>";
    }
}