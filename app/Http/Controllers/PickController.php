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

class PickController extends Controller
{
    public function __construct(PickModel $pick)
    {
        $this->model = $pick;
        $this->mainIndex = route('pick.index');
        $this->mainTitle = '拣货';
        $this->viewPath = 'pick.';
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
        $packages = PackageModel::where('status','未处理')->get();
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
}