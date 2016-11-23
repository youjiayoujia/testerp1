<?php
/**
 * 渠道展示编码
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/10/17
 * Time: 下午3:29
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\TransportModel;

class TransportController extends Controller
{
    public function __construct(TransportModel $transport)
    {
        $this->model = $transport;
        $this->mainIndex = route('logisticsTransport.index');
        $this->mainTitle = '渠道展示编码';
        $this->viewPath = 'logistics.transport.';
    }
}