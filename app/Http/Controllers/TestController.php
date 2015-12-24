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


class TestController extends Controller
{
    public function test()
    {
        $test = [
            'ok' => 'name',
            'test' => 'test1',
            'test2' => 'test3',
        ];

        $buf = json_encode($test);
        var_dump($buf);

        $buf1 = '{"ok":"name","test":"test1","test2":"test3"}';
        $buf2 = json_decode($buf, true);

        var_dump($buf2);
    }
}