<?php
/**
 * 跟踪号控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/28
 * Time: 上午10:50
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CodeModel as CodeModel;
use App\Models\LogisticsModel as LogisticsModel;

class CodeController extends Controller
{
    public function __construct(CodeModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('logisticsCode.index');
        $this->mainTitle = '跟踪号';
        $this->viewPath = 'logistics.code.';
    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses'=>$this->getLogistics(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses'=>$this->getLogistics(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function getLogistics(){

        return LogisticsModel::all();
    }
}