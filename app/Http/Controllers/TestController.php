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
use App\Modules\Channels\Amazon\AmazonModule;


class TestController extends Controller
{
    public function test()
    {
        $model = new AmazonModule();
        echo "<pre>";
        var_dump($model->getXML([['2', '3', '4', '5', '6', ['item1'=>'12']]],'32'));
        echo "</pre>";
    }
}