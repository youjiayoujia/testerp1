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

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $arr = request()->all();
        foreach($arr as $key => $value) {
        	if(empty($value)) {
        		unset($arr[$key]);
        	}
        }
        //var_dump($arr);exit;
        $model = $this->model->create($arr);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', base64_encode(serialize($model)));
        return redirect($this->mainIndex);
    }
}