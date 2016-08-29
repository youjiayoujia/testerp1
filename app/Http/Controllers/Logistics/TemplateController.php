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

    /**
     * 跳转面单模版页面
     */
    public function view($id)
    {
        $model = $this->model->find($id);

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];

        return view($this->viewPath . 'tpl.' . explode('.', $model->view)[0], $response);
    }

    /**
     * 保存
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->create(request()->all());
//        $path = '../app/Views/logistics/template/tpl/';
//        fopen($path . request()->all()['view'], 'w');
        return redirect($this->mainIndex);
    }

}