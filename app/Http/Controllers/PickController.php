<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use App\Models\PickModel;
use App\Models\PackageModel;

class PickController extends Controller
{
    public function __construct(PickModel $pick)
    {
        $this->model = $pick;
        $this->mainIndex = route('pick.index');
        $this->mainTitle = '拣货';
        $this->viewPath = 'pick.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'model' => $model, 
            'packages' => $model->packages,
            'orderitems' => $model->packages->orderitems->with('items'),
        ];

        return view($this->viewPath.'show', $response);
    }

    public function dd()
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'model' => $model, 
            'packages' => $model->packages,
            'orderitems' => $model->packages->orderitems->with('items'),
        ];

        return view($this->viewPath.'fj', $response);
    }

    public function ajaxCreatePick()
    {
        $type = 'oo';
        $packages = PackageModel::where(['status'=>'PROCESSING'])->get();
        var_dump($packages->toArray());
        exit;
        $arr = [];
        $arr1 = [];
        $arr2 = [];
        foreach($packages as $package)
        {
            if($package->type == '单单')
            {
                $this->model->getddanPickListArray($package->orderitem->items->item_id, $arr);  
            }
            if($package->type == '单多')
            {
                $this->model->getdduoPickListArray($package->orderitem->items->item_id, $package->orderitem->quantity, $arr1);  
            }
            if($package->type == '多多')
            {
                //计算出package的得分
                $arr2[$package->id] = $package_score;
            }
        }

        $this->model->createdd($arr);
        $this->model->createddduo($arr1);
        $this->model->createduoduo($arr2);
        echo json_encode('111');
    }

    public function ajaxType()
    {
        $type = request()->input('type');

    }
}