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
use Tool;

class PickListController extends Controller
{
    public function __construct(PickListModel $pick)
    {
        $this->model = $pick;
        $this->mainIndex = route('pickList.index');
        $this->mainTitle = '拣货';
        $this->viewPath = 'pick.';
        $this->middleware('StockIOStatus');
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
            'barcode' => Tool::barcodePrint($model->picknum, "C128"),
        ];

        return view($this->viewPath.'print', $response);
    }

    public function performanceStatistics()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '效能统计'),
        ];

        return view($this->viewPath.'statistics', $response);
    }

    public function statisticsProcess()
    {
        $start_time = request('start_time');
        $end_time = request('end_time');
        $pick = $this->model->whereBetween('pick_at', [$start_time, $end_time])->get();
        $inbox = $this->model->whereBetween('inbox_at', [$start_time, $end_time])->get();
        $pack = $this->model->whereBetween('pack_at', [$start_time, $end_time])->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__, '效能统计'),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'pick' => $pick->groupBy('pick_by'),
            'pickNum' => $pick->count(),
            'skuNum' => $pick->sum('sku_num'),
            'goodsNum' => $pick->sum('goods_quantity'),
            'inbox' => $inbox->groupBy('inbox_by'),
            'inboxPickNum' => $inbox->count(),
            'inboxSkuNum' => $inbox->sum('sku_num'),
            'inboxGoodsNum' => $inbox->sum('goods_quantity'),
            'pack' => $pack->groupBy('pack_by'),
            'packPickNum' => $pack->count(),
            'packSkuNum' => $pack->sum('sku_num'),
            'packGoodsNum' => $pack->sum('goods_quantity'),
        ];

        return view($this->viewPath.'statisticsProcess', $response);
    }

    public function indexPrintPickList($content)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'content' => $content,
        ];

        return view($this->viewPath.'choosePickList', $response);
    }

    public function processBase()
    {
        $flag = request('flag');
        $picknum = request('picknum');
        switch($flag) {
            case 'print':
                $model = $this->model->where('picknum', $picknum)->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->printPickList($model->id);
                break;
            case 'single':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'SINGLE'])->whereIn('status', ['PICKING', 'PICKED', 'PACKAGEING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->pickListPackage($model->id);
                break;
            case 'singleMulti':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'SINGLEMULTI'])->whereIn('status', ['PICKING', 'PICKED', 'PACKAGEING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->pickListPackage($model->id);
                break;
            case 'inbox':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'MULTI'])->whereIn('status', ['PICKED', 'PICKING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->inbox($model->id);
                break;
            case 'multi':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'MULTI'])->whereIn('status', ['INBOXED', 'PACKAGEING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->pickListPackage($model->id);
                break;
        }
        
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
            'packages' => $model->package,
            'logistics' => LogisticsModel::all(),
        ];
        if($model->type == 'MULTI')
            return view($this->viewPath.'packageMulti', $response);
        
        return view($this->viewPath.'package', $response);
    }

    public function oldPrint()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'oldPrint', $response);
    }

    public function updatePrint()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => LogisticsModel::all(),
        ];

        return view($this->viewPath.'updatePrint', $response);
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
        $obj->update(['status' => 'INBOXED', 'inbox_by' => request()->user()->id, 'inbox_at' => date('Y-m-d H:i:s', time())]);
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
        $model->update(['status' => 'PACKAGED', 'pack_by' => request()->user()->id, 'pack_at' => date('Y-m-d H:i:s', time())]);

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
            'logisticses' => LogisticsModel::all(),
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
        if(request()->has('logistics')) {
            foreach(request()->input('logistics') as $logistic_id) {
                $packages = PackageModel::where(['status'=>'PROCESSING', 'logistics_id'=>$logistic_id, 'is_auto'=>'1'])->where(function($query){
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

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '已生成'));
    }
}