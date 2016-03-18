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

use Session;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\Stock\TakingFormModel;
use App\Models\AModel;
use App\Models\BModel;
use App\Models\CModel;

class TestController extends Controller
{
    public function test()
    {
        if($a = '1')
            var_dump($a);
    }

    public function test1($url)
    {
        var_dump($url);
    }

}