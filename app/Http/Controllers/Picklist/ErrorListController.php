<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers\Picklist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pick\ErrorListModel;
use App\Models\PackageModel;

class ErrorListController extends Controller
{
    public function __construct(ErrorListModel $errorList)
    {
        $this->model = $errorList;
        $this->mainIndex = route('errorList.index');
        $this->mainTitle = '拣货单异常';
        $this->viewPath = 'pick.errorList.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $packages = PackageModel::where('status', 'ERROR')->get();
        foreach($packages as $package) 
        {
            if(!$this->model->where('package_id', $package->id)->count()) {
                $this->model->create(['picklist_id'=>$package->picklist_id, 'package_id'=>$package->id]);
            }
        }

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];

        return view($this->viewPath . 'index', $response);
    }

    /**
     * 列表显示 
     *
     * @param $id 
     * @return view
     *
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model, 
            'packages' => $model->package()->with('items')->get(),
        ];

        return view($this->viewPath.'show', $response);
    }

    public function ajaxProcess()
    {
        $id = request('id');
        $model = $this->model->find($id);
        if(!$model) {
            return json_encode('false');
        }
        $package = PackageModel::find($model->package_id);
        if(!$package) {
            return json_encode('false');
        }
        $package->update(['status' => 'SHIPPED']);
        $model->update(['status' => '1', 'process_by' => request()->user()->id, 'process_time' => date('Y-m-d h:m:s', time())]);
        return json_encode('true');
    }
}