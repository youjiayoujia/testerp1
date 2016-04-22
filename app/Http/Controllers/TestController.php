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

use Illuminate\Http\Request;
use App\Modules\SingleChannelModule;

class TestController extends Controller
{
    public function test()
    {
        $model = new SingleChannelModule('Amazon');
        $model->listOrderItems();
    }
}