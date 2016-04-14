<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Excel;
use Session;
use Illuminate\Http\Request;
use App\Models\WarehouseModel;
use App\Models\CurrencyModel;

class TestController extends Controller
{
    public function __construct(WarehouseModel $warehouse)
    {
        $this->model = $warehouse;
        $this->mainIndex = route('warehouse.index');
        $this->mainTitle = '仓库';
        $this->viewPath = 'warehouse.';
    }

    public function test()
    {
        $cu = new CurrencyModel;
        $tmp = $cu->getRate('  dCAD');
        var_dump($tmp);
    }
}