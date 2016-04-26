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
use App\Models\ItemModel;

class TestController extends Controller
{
    public function test()
    {
        ItemModel::find(15)->holdout(19, 2, 'ADJUSTMENT', 11, 'test');
        var_dump('ok');
    }   
}