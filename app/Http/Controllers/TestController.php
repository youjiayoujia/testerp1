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
use App\Repositories\Stock\AdjustmentRepository;
use App\Models\Stock\AdjustmentModel;


class TestController extends Controller
{
    public function __construct(AdjustmentRepository $adjustment)
    {
        $this->adjustment = $adjustment;
    }


    public function test()
    {
        $arr['name'][0] = 'test';
        $arr['name'][2] = 'ok';

        $arr['sku'][0] = 'mc';
        $arr['sku'][1] = 'dj';

        var_dump(array_values($arr['name']));
    }
}