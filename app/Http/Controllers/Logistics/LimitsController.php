<?php
/**
 * 物流先知控制器
 *
 * Created by MC.
 * Date: 16/4/19
 * Time: 上午10:50
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\LimitsModel;


class LimitsController extends Controller
{
    public function __construct(LimitsModel $limits)
    {
        $this->model = $limits;
        $this->mainIndex = route('logisticsLimits.index');
        $this->mainTitle = '物流限制';
        $this->viewPath = 'logistics.limits.';
    }
}