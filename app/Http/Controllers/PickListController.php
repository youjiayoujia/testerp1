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
use App\Models\Pick\ErrorListModel;
use App\Models\ItemModel;
use DB;
use App\Models\UserModel;
use Exception;
use App\Models\WarehouseModel;

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
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $model = '';
        $user = UserModel::find(request()->user()->id);
        $warehouseId = $user->warehouse_id;
        if(request()->has('checkid')) {
            $model = $this->model->where('pick_by', request('checkid'))->where('warehouse_id', $warehouseId)->whereBetween('pick_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))]);
            if(request()->has('twenty')) {
                $model = $this->model->where('pick_by', request('checkid'))->where('created_at', '<', date('Y-m-d', strtotime('-1 day')));
            }
            if(request()->has('flag')) {
                $model = $this->model->where('pick_by', request('checkid'))->whereBetween('created_at', [date('Y-m-d', strtotime('now')),date('Y-m-d', strtotime('1 day'))])->whereNotIn('status', ['PACKAGED']);
            }
        }
        $today_print = $this->model->whereBetween('print_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))])->count();
        $allocate = $this->model->whereBetween('pick_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))])
                    ->get()
                    ->filter(function($single){
                        return $single->pick_by != 0;
                    })->count();
        request()->flash();
        $tmp = $this->model->where('warehouse_id', $warehouseId);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, !empty($model) ? $model : ($this->model->where('warehouse_id', $warehouseId))),
            'mixedSearchFields' => $this->model->mixed_search,
            'today_print' => $today_print,
            'allocate' => $allocate,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function printInfo()
    {
        $user = request('user');
        $id = request('id');
        $model = $this->model->find($id);
        if (!$model) {
            return json_encode(false);
        }
        $model->printRecords()->create(['user_id' => $user]);
        return json_encode(true);
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
        $five = $model->printRecords()->orderBy('created_at', 'desc')->take('5')->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model, 
            'user' => request()->user()->id,
            'five' => $five
        ];

        return view($this->viewPath.'show', $response);
    }

    public function changePickBy()
    {
        $picklist = request('picklist');
        $pickBy = request('pickBy');
        $id = request('id');
        $model = $this->model->where('picknum', $picklist)->with('pickListItem')->first();
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($model);
        if(!$model) {
            return json_encode(false);
        }
        if($id == 1) {
            if($model->pick_by == 0 && $model->status == 'NONE') {
                $model->update(['pick_by' => $pickBy, 'pick_at' => date('Y-m-d H:i:s', time()), 'status' => 'PICKING']);
            } else {
                $model->update(['pick_by' => $pickBy, 'pick_at' => date('Y-m-d H:i:s', time())]);
            }
        } else {
            $model->update(['pick_by' => $pickBy]);
        }
        $to = json_encode($model);
        $this->eventLog($name, '修改拣货人员,id='.$model->id, $to, $from);

        return json_encode(true);
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
        $ids = explode(",", $id);
        $html = '';
        foreach($ids as $id) {
            $model = $this->model->find($id);
            $from = json_encode($model);
            $name = UserModel::find(request()->user()->id)->name;
            if (!$model) {
                continue;
            }
            foreach($model->package as $package) {
                $to = json_encode($package);
                $package->eventLog($name, '包裹打印', $to);
            }
            $model->printRecords()->create(['user_id' => request()->user()->id]);
            if($model->status == 'NONE') {
                $model->update(['status' => 'PRINTED', 'print_at' => date('Y-m-d H:i:s', time())]);
            }
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'model' => $model,
                'size' => $model->logistics ? ($model->logistics->template ? $model->logistics->template->size : '暂无面单尺寸信息') : '暂无面单尺寸信息',
                'picklistitemsArray' => $model->pickListItem()->get()->sortBy(function($single,$key){
                return $single->position ? $single->position->name : 1;
              })->chunk('25'),
            ];
            $this->eventLog($name, '打印拣货单,id='.$model->id, $from, $from);
            $html .= view($this->viewPath.'print', $response);
        }
        
        return $html;
    }

    public function confirmPickBy()
    {
        $model = $this->model->find(request('pickId'));
        $from = json_encode($model);
        $name = UserModel::find(request()->user()->id)->name;
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $pickBy = request('pickBy');
        $single = $this->model->where('pick_by', $pickBy)->orderBy('created_at')->first();
        if($single) {
            if($single->status == 'PICKING') {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '上次拣货未完成,不可分配新的'));
            }
        }
        $model->update(['pick_by' => request('pickBy'), 'pick_at' => date('Y-m-d H:i:s', time()), 'status' => 'PICKING']);
        $to = json_encode($model);
        $this->eventLog($name, '修改拣货人员,id='.$model->id, $to, $from);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '拣货人员修改成功'));
    }

    public function printPackageDetails($id, $status)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'packages' => ($status != 'all' ? $model->package()->where('status', $status)->withTrashed()->get() : $model->package()->withTrashed()->get()),
        ];

        return view($this->viewPath.'printPackages', $response);
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
            'metas' => $this->metas(__FUNCTION__,'包装'),
            'content' => $content,
        ];

        return view($this->viewPath.'choosePickList', $response);
    }

    public function pickCode($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];

        return view($this->viewPath.'code', $response);
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
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'SINGLE'])->whereIn('status', ['PACKAGEING', 'PICKING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->pickListPackage($model->id);
                break;
            case 'singleMulti':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'SINGLEMULTI'])->whereIn('status', ['PACKAGEING', 'PICKING'])->first();
                if(!$model) {
                    return $this->indexPrintPickList($flag);
                }
                return $this->pickListPackage($model->id);
                break;
            case 'inbox':
                $model = $this->model->where(['picknum' => $picknum, 'type' => 'MULTI'])->where('status', 'PICKING')->first();
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
            case 'forceOut':
                $picknum = request('picknum');
                $model = PackageModel::find($picknum);
                if(!$model) {
                    $model = PackageModel::where('tracking_no', $picknum)->first();
                }
                if(!$model) {
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '对应包裹不存在.'));
                }
                $response = [
                    'metas' => $this->metas(__FUNCTION__, '强制出库'),
                    'package' => $model,
                ];
                return view($this->viewPath.'forceOut', $response);
                break;
        }
        
    }

    public function printException() 
    {
        $arr = explode(',', request('arr'));
        $buf = [];
        foreach($arr as $key => $value) {
            if($value) {
                $tmp = explode('.', $value);
                $buf[$tmp[0]][$tmp[1]] = $tmp[2];
            }
        }
        $barcodes = [];
        $packages = [];
        foreach($buf as $key => $barcode) {
            $packages[$key] = PackageModel::find($key);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'buf' => $buf,
            'packages' => $packages
        ];

        return view($this->viewPath.'printException', $response);

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
        if(!in_array($model->status, ['PICKING', 'INBOXED', 'PACKAGEING'])) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '包裹状态不对不能包装'));
        }
        if($model->status == 'PICKING') {
            $model->update(['status' => 'PACKAGEING']);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__,'包装'),
            'model' => $model,
            'pickListItems' => $model->pickListItem,
            'packages' => $model->package()->withTrashed()->get(),
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
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($obj);
        $obj->update(['status' => 'INBOXED', 'inbox_by' => request()->user()->id, 'inbox_at' => date('Y-m-d H:i:s', time())]);
        foreach($obj->package as $package)
        {
            $package->status = 'PICKED';
            $package->save();
        }
        $this->eventLog($name, '分拣完成,id='.$obj->id, $from);

        return redirect(route('package.flow'));
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
        $picklist = $this->model->find($id);
        if (!$picklist) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $sum = 0;
        foreach($picklist->package()->withTrashed()->get() as $package)
        {
            if(!in_array($package->status, ['PACKED', 'SHIPPED'])) {
                $package->status = 'ERROR';
                $package->save();
                foreach($package->items()->withTrashed()->get() as $packageItem) {
                    $sum += $packageItem->quantity - $packageItem->picked_quantity;
                    $errorLists = ErrorListModel::where('item_id', $packageItem->item_id)->get();
                    if($errorLists->count()) {
                        foreach($errorLists as $errorList) {
                            $errorList->update(['packageNum' => $errorList->packageNum.','.$package->id]);
                        }
                    } else {
                        $item = ItemModel::find($packageItem->item_id);
                        foreach($item->stocks as $key => $stock) {
                            $model = ErrorListModel::where(['item_id'=> $stock->item_id, 'warehouse_position_id' => $stock->warehouse_position_id])->first();
                            if(!$model) {
                                $model = ErrorListModel::create([
                                        'item_id' => $stock->item_id,
                                        'packageNum' => $package->id,
                                        'warehouse_position_id' => $stock->warehouse_position_id,
                                        'warehouse_id' => $stock->warehouse_id,
                                        'quantity' => $stock->all_quantity,
                                    ]);
                                continue;
                            }
                            $model->update(['packageNum' => $model->packageNum.','.$package->id]);
                        }
                    }
                }
            } 
        $picklist->update(['status' => 'PACKAGED', 'pack_by' => request()->user()->id, 'pack_at' => date('Y-m-d H:i:s', time()),
                            'quantity' => $sum]);
        }

        return redirect(route('package.flow'));
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
        $package = PackageModel::find($package_id);
        $order = $package->order;
        if($order->status == 'REVIEW') {
            $package->update(['status' => 'ERROR']);
            return json_encode(false);
        }
        if($package) {
            $package->eventLog(UserModel::find(request()->user()->id)->name, '已包装', json_encode($package));
            $item = $package->items->first();
            $item->update(['picked_quantity' => $item->quantity]);
            $order = $package->order;
            $package->status = 'PACKED';
            $package->save();
            $picklistItems = $package->picklistItems;
            foreach($picklistItems as $picklistItem) {
                $picklistItem->packed_quantity += $package->items->where('item_id', $picklistItem->item_id)->first()->quantity;
                $picklistItem->save();
            }
            $buf = 1;
            foreach($order->packages as $childPackage) {
                if($childPackage->status != 'PACKED') {
                    $buf = 0;
                }
            }
            if($buf) {
                foreach($package->items as $packageItem) {
                    $packageItem->orderItem->update(['status' => 'PACKED']);
                }
                $order->update(['status' => 'PACKED']);
            }
            DB::beginTransaction();
            try {
                foreach($package->items as $packageItem) {
                    $flag = $packageItem->item->holdout($packageItem->warehouse_position_id,
                                                $packageItem->quantity,
                                                'PACKAGE',
                                                $packageItem->id);
                    if(!$flag) {
                        throw new Exception('包裹出库库存有问题');
                    }
                    $packageItem->orderItem->update(['status' => 'SHIPPED']);
                }
            } catch (Exception $e) {
                DB::rollback();
                return json_encode('unhold');
            }
            DB::commit();
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
        $userId = request()->user()->id;
        $user = UserModel::find($userId);
        $warehouseId = $user->warehouse_id;
        $logisticses = WarehouseModel::find($warehouseId)->logistics;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'logisticses' => $logisticses->groupBy('template.size'),
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
        $sum = 0;
        $warehouse_id = UserModel::find(request()->user()->id)->warehouse_id;
        if(!$warehouse_id) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '人员没有所属仓库'));
        }
        if(!request()->has('mixed') && request()->has('logistics')) {
            foreach(request()->input('logistics') as $logistic_id) {
                foreach(request('package') as $key => $type) {
                    $packages = PackageModel::where(['status'=>'PROCESSING', 'logistics_id'=>$logistic_id, 'is_auto'=>'1', 'type' => $type, 'warehouse_id' => $warehouse_id])
                    ->where(function($query){
                        if(request()->has('channel')) {
                            $query =$query->whereIn('channel_id', request('channel'));
                        }
                    })->get();
                    $sum += $packages->count();
                    if($packages->count()) {
                        $this->model->createPickListItems($packages);
                        $this->model->createPickList((request()->has('singletext') ? request()->input('singletext') : '25'), 
                                                     (request()->has('multitext') ? request()->input('multitext') : '20'), $logistic_id, $warehouse_id);

                    }
                }
            }
        } elseif(request()->has('mixed') && request()->has('logistics')) {
            foreach(request('package') as $key => $type) {
               $packages = PackageModel::where(['status'=>'PROCESSING', 'is_auto'=>'1', 'type' => $type, 'warehouse_id' => $warehouse_id])
               ->where(function($query){
                    if(request()->has('logistics')) {
                        $query = $query->whereIn('logistics_id', request('logistics'));
                    }
                })->where(function($query){
                    if(request()->has('channel')) {
                        $query = $query->whereIn('channel_id', request('channel'));
                    }
                })->get();
                $sum += $packages->count();
                if($packages->count()) {
                    $this->model->createPickListItems($packages);
                    $this->model->createPickListFb((request()->has('singletext') ? request()->input('singletext') : '25'), 
                                                 (request()->has('multitext') ? request()->input('multitext') : '20'), $warehouse_id);
                } 
            }
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', $sum.'个包裹已加入生成拣货单'));
    }
}