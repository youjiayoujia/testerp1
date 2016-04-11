<?php
/**
 * 拣货单控制器
 * 处理拣货单相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 16/3/18
 * Time: 17:35pm
 */

namespace App\Http\Controllers;

use App\Models\PickListModel;
use App\Models\PackageModel;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\Pick\PackageScoreModel;

class PickListController extends Controller
{
    public function __construct(PickListModel $pick)
    {
        $this->model = $pick;
        $this->mainIndex = route('pickList.index');
        $this->mainTitle = '拣货';
        $this->viewPath = 'pick.';
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

    /**
     * 打印拣货单 
     *
     * @param $id
     * @return view
     *
     */
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

    /**
     * 打包页面
     *
     * @param $id
     * @return view
     *
     */
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

    /**
     * 删除 
     *
     * @param $id 
     *
     */
    public function destroy($id) 
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $picklistItems = $model->pickListItem;
        foreach($picklistItems as $picklistItem)
        {
            $listItemPackages = $picklistItem->pickListItemPackages;
            foreach($listItemPackages as $listItemPackage)
            {
                $listItemPackage->delete();
            }
            $picklistItem->delete();
        }
        if($model->type == 'MULTI')
        {
            $packages = $model->package;
            foreach($packages as $package) {
                $score = PackageScoreModel::where('package_id', $package->id)->first();
                $score->delete();
            } 
        }
        $model->delete();

        return redirect($this->mainIndex);
    }

    /**
     * 拣货单结果提交
     *
     * @param $id
     * @return view
     *
     */
    public function inboxStore($id)
    {
        $obj = $this->model->find($id);
        $obj->status = 'INBOXED';
        $obj->save();
        foreach($obj->package as $package)
        {
            $package->status = 'PICKED';
            $package->save();
        }

        return redirect($this->mainIndex);
    }

    /**
     * 提交的打包 
     *
     * @param $id
     * @return view
     *
     */
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
            if($package->status != 'PACKED') {
                $package->status = 'ERROR';
                $package->save();
            }    
        }

        return redirect($this->mainIndex);
    }

    /**
     * ajax  更新packageitem信息
     *
     * @param none
     * @return json
     *
     */
    public function ajaxPackageItemUpdate()
    {
        $package_id = trim(request()->input('package_id'));
        $sku = trim(request()->input('sku'));
        $package = PackageModel::find($package_id);
        if($package) {
            $items = $package->items;
            $flag = 1;
            foreach($items as $item) {
                if($item->item->sku == $sku && ($item->picked_quantity + 1) <= $item->quantity) {
                    $item->picked_quantity += 1;
                    $item->save();
                }
                if($item->picked_quantity != $item->quantity)
                    $flag = 0;
            }
            if($flag == 1) {
                $package->status = 'PACKED';
                $package->save();
            }
            return json_encode('1');
        }

        return json_encode('false');
    }

    /**
     * 分拣界面 
     *
     * @param $id 
     * @return view
     *
     */
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

    /**
     * 生成拣货单页面 
     *
     * @param none
     * @return view
     *
     */
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

    /**
     * 按条件挑选package
     *
     * @param none
     * @return view
     *
     */
    public function createPickStore()
    {   
        if(request()->has('logistic')) {
            foreach(request()->input('logistic') as $logistic_id) {
                $packages = PackageModel::where(['status'=>'PROCESSING', 'logistic_id'=>$logistic_id, 'is_auto'=>'1'])->where(function($query){
                    if(request()->has('package')) {
                        foreach(request()->input('package') as $key => $package)
                            $query = ($key == 0 ? $query->where('type', $package) : $query->orwhere('type', $package));
                    }
                })->where(function($query){
                    if(request()->has('channel')) {
                        foreach(request()->input('channel') as $key => $channel)
                            $query = ($key == 0 ? $query->where('channel_id', $channel) : $query->orwhere('channel_id', $channel));
                    }
                })->get();
                if(count($packages)) {
                    $this->model->createPickListItems($packages);
                    $this->model->createPickList((request()->has('singletext') ? request()->input('singletext') : '25'), 
                                                 (request()->has('multitext') ? request()->input('multitext') : '20'), $logistic_id);
                }
            }
        } else {
            $packages = PackageModel::where(['status'=>'PROCESSING', 'is_auto'=>'1'])->where(function($query){
                if(request()->has('package')) {
                    foreach(request()->input('package') as $key => $package)
                        $query = ($key == 0 ? $query->where('type', $package) : $query->orwhere('type', $package));
                }
            })->where(function($query){
                if(request()->has('channel')) {
                    foreach(request()->input('channel') as $key => $channel)
                        $query = ($key == 0 ? $query->where('channel_id', $channel) : $query->orwhere('channel_id', $channel));
                }
            })->get();
            if(count($packages)) {
                $this->model->createPickListItems($packages);
                $this->model->createPickListFb((request()->has('singletext') ? request()->input('singletext') : '25'), 
                                             (request()->has('multitext') ? request()->input('multitext') : '20'));
            }
        }

        return redirect($this->mainIndex);
    }
}