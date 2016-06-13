<?php
/**
 * 面单模版控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/13
 * Time: 下午2:41
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\TemplateModel;

class TemplateController extends Controller
{
    public function __construct(TemplateModel $template)
    {
        $this->model = $template;
        $this->mainIndex = route('logisticsTemplate.index');
        $this->mainTitle = '面单模版';
        $this->viewPath = 'logistics.template.';
    }

}