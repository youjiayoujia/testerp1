<?php
/**
 * 物流对账控制器
 * 处理物流对账相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ShipmentCostController extends Controller
{
    public function __construct(WrapLimitsModel $wrapLimits)
    {
        $this->model = $wrapLimits;
        $this->mainIndex = route('wrapLimits.index');
        $this->mainTitle = '包装限制';
        $this->viewPath = 'wrapLimits.';
    }
}