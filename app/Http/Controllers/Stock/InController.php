<?php
/**
 * 入库控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\InModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;

class InController extends Controller
{
    public function __construct(InModel $in)
    {
        $this->model = $in;
        $this->mainIndex = route('stockIn.index');
        $this->mainTitle = '入库';
        $this->viewPath = 'stock.in.';
    }
}