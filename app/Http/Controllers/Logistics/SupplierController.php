<?php
/**
 * 物流商控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:42
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\SupplierModel;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('logisticsSupplier.index');
        $this->mainTitle = '物流商';
        $this->viewPath = 'logistics.supplier.';
    }

}