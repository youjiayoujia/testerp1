<?php
/**
 * 回邮模版控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/12
 * Time: 上午9:08
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\EmailTemplateModel;

class EmailTemplateController extends Controller
{
    public function __construct(EmailTemplateModel $emailTemplate)
    {
        $this->model = $emailTemplate;
        $this->mainIndex = route('logisticsEmailTemplate.index');
        $this->mainTitle = '回邮模版';
        $this->viewPath = 'logistics.emailTemplate.';
    }

}