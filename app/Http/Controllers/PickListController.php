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

use App\Models\PickListModel;
use App\Models\PackageModel;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;

class PickListController extends Controller
{
    public function __construct(PickListModel $pick)
    {
        $this->model = $pick;
        $this->mainIndex = route('pickList.index');
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
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model, 
            'packages' => $model->package()->with('items')->get(),
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
        $packages = PackageModel::where(['status'=>'PROCESSING', 'type'=>'SINGLE'])->get();
        $arr = [];
        $arr1 = [];
        $arr2 = [];
        foreach($packages as $package)
        {
            if($package->type == 'SINGLE')
            {
                $this->model->getSinglePickListArray($package, $arr);  
            }
            if($package->type == 'SINGLEMULTI')
            {
                $this->model->getSingleMultiPickListArray($package, $arr1);  
            }
            // if($package->type == 'MULTI')
            // {
            //     //计算出package的得分
            //     $arr2[$package->id] = $package_score;
            // }
        }
        // var_dump($arr1);exit;
        if($arr)
        {
            $this->model->createSingle($arr);
        }
        if($arr1)
        {
            $this->model->createSingleMulti($arr1);
        }

        echo json_encode('111');
    }

    public function printPickList($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($model->status == 'NONE') {
            $model->status = 'PICKING';
            $model->save();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'picklistitems' => $model->pickListItem,
        ];

        return view($this->viewPath.'print', $response);
    }

    public function pickListPackage($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->status = 'PACKAGEING';
        $model->save();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'pickListItems' => $model->pickListItem,
            'packages' => $model->package
        ];
    
        return view($this->viewPath.'package', $response);
    }

    public function inboxStore($id)
    {
        $obj = $this->model->find($id);
        foreach($obj->package as $package)
        {
            $package->status = 'PICKED';
            $package->save();
        }

        return redirect($this->mainIndex);
    }

    public function packageStore($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->status = 'PACKAGED';
        $model->save();

        foreach($model->package as $package)
        {
            $package->status = 'PACKED';
            $package->save();
        }

        return redirect($this->mainIndex);
    }

    public function ajaxPackageItemUpdate()
    {
        $package_id = request()->input('package_id');
        $sku = request()->input('sku');
        $package = PackageModel::find($package_id);
        if($package) {
            $items = $package->items;
            $flag = 1;
            foreach($items as $item) {
                if($item->items->sku == $sku && ($item->picked_quantity + 1) <= $item->quantity) {
                    $item->picked_quantity += 1;
                    $item->save();
                }
                if($item->picked_quantity != $item->quantity)
                    $flag = 0;
            }
            if($flag == 1) {
                $package->status = 'PICKED';
                $package->save();
            }
            return json_encode('1');
        }

        return 'false';
    }

    public function inbox($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'pickListItems' => $model->pickListItem,
            'packages' => $model->package
        ];

        return view($this->viewPath.'inbox', $response);
    }

    public function ajaxInboxResult()
    {
        $sku = 'wtRnY-bb-';
        $id = 60;
        $pickList = $this->model->find($id);
        $packages = $pickList->package;
        foreach($packages as $package) {
            foreach($package->items as $packageitem) {
                if(1) {//数量<原有数量 
                    $packageitem->quantity += 1;
                    var_dump($package->id - $packages->first()->id + 1);
                }
            }
        }
    }

    public function createPick()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'logistics' => LogisticsModel::all(),
            'count' => PackageModel::where('status','PROCESSING')->count(),
        ];

        return view($this->viewPath.'createPick', $response);
    }

    public function createPickStore()
    {   
        if(request()->has('logistic')) {
            foreach(request()->input('logistic') as $logistic_id) {
                $packages = PackageModel::where(['status'=>'PROCESSING', 'logistic_id'=>$logistic_id])->where(function($query){
                    if(request()->has('package')) {
                        foreach(request()->input('package') as $key => $package)
                            if($key == 0)
                                $query = $query->where('type', $package);
                            else
                                $query = $query->orwhere('type', $package);
                    }
                })->where(function($query){
                    if(request()->has('channel')) {
                        foreach(request()->input('channel') as $key => $channel)
                            if($key == 0)
                                $query = $query->where('channel_id', $channel);
                            else
                                $query = $query->orwhere('channel_id', $channle);
                    }
                })->get();
                if(count($packages)) {
                    $this->model->createPickListItems($packages);
                    $this->model->createPickList((request()->has('singletext') ? request()->input('singletext') : '25'), 
                                                 (request()->has('multitext') ? request()->input('multitext') : '20'),$logistic_id);
                }
            }
        }

        return redirect($this->mainIndex);
    }
}