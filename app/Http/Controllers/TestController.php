<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\Stock\TakingFormModel;

class TestController extends Controller
{
    public function test()
    {
        $fp = curl_init();
        curl_setopt($fp, CURLOPT_URL, 'http://www.baidu.com');
        curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
        $test = curl_exec($fp);
        curl_close($fp);
        echo "<pre>";
        print_r($test);
        echo "</pre>";
    }
}