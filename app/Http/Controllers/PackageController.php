<?php
/**
 * 包裹控制器
 *
 * 2016-03-09
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use Tool;
use Excel;
use App\Models\StockModel;
use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\ItemModel;
use App\Models\LogisticsModel;
use App\Models\Warehouse\PositionModel;
use App\Models\PickListModel;
use App\Jobs\PlaceLogistics;
use App\Jobs\AssignLogistics;
use App\Models\WarehouseModel;
use DB;
use Exception;
use App\Jobs\AssignStocks;
use App\Models\NumberModel;
use App\Models\UserModel;

class PackageController extends Controller
{
    public function __construct(PackageModel $package)
    {
        $this->model = $package;
        $this->mainIndex = route('package.index');
        $this->mainTitle = '包裹';
        $this->viewPath = 'package.';
        $this->middleware('StockIOStatus');
    }

    /**
     *  将包裹need放到匹配队列中 
     *
     *  @param none
     *  @return redirect
     *
     */
    public function putNeedQueue()
    {
        $len = 1000;
        $start = 0;
        $packages = $this->model->where('status', 'NEED')->skip($start)->take($len)->get();
        $name = UserModel::find(request()->user()->id)->name;
        while ($packages->count()) {
            foreach ($packages as $package) {
                $job = new AssignStocks($package);
                $job->onQueue('assignStocks');
                $this->dispatch($job);
                $to = base64_encode(serialize($package));
                $this->eventLog($name, '包裹放匹配库存队列', $to, $to);
            }
            $start += $len;
            unset($packages);
            $packages = $this->model->where('status', 'NEED')->skip($start)->take($len)->get();
        }
        return redirect(route('dashboard.index'))->with('alert', $this->alert('success', '添加至assignStocks队列成功'));
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $buf = '';
        if(request()->has('outer')) {
            $outer = request('outer');
            $channelId = request('id');
            if($outer == 'all') {
                $buf = $this->model->where('status', 'PICKING')
                                   ->where('channel_id', $channelId)
                                   ->where('created_at', '<', date('Y-m-d H:i:s', strtotime('-3 days')));
            } else {
                $flag = request('flag');
                if($flag == 'less') {
                    $buf = $this->model->where('status', 'PICKING')
                                   ->where('channel_id', $channelId)
                                   ->where('warehouse_id', $outer)
                                   ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-3 days')));
                } else {
                    $buf = $this->model->where('status', 'PICKING')
                                   ->where('channel_id', $channelId)
                                   ->where('warehouse_id', $outer)
                                   ->where('created_at', '<', date('Y-m-d H:i:s', strtotime('-3 days')));
                }
            }
        }
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList(!empty($buf) ? $buf : $this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath . 'index', $response);
    }

    public function logisticsDelivery()
    {
        $date = request()->input('date');
        if(!$date) {
            $date = date('Y-m-d');
        }
        $data = [];
        $count = $this->model->where('logistics_id', '!=', 0)
            ->where('shipped_at', '>=', $date . ' 00:00:00')
            ->where('shipped_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($date))) . ' 00:00:00')
            ->count();
        $totalWeight = 0;
        $logisticses = LogisticsModel::where('is_enable', 1)->get();
        foreach($logisticses as $key => $logistics) {
            $data[$key]['logisticsName'] = $logistics->name;
            $data[$key]['logisticsId'] = $logistics->id;
            $data[$key]['logisticsPriority'] = $logistics->priority;
            $data[$key]['weight'] = 0;
            $data[$key]['percent'] = 0 . '%';
            $packages = $this->model
                ->where('logistics_id', $logistics->id)
                ->where('shipped_at', '>=', $date . ' 00:00:00')
                ->where('shipped_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($date))) . ' 00:00:00');
            foreach($packages->get() as $package) {
                $data[$key]['weight'] += $package->weight;
            }
            $data[$key]['quantity'] = $packages->count();
            $totalWeight += $data[$key]['weight'];
            if($count) {
                $data[$key]['percent'] = round($data[$key]['quantity'] / $count * 100, 2) . '%';
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'datas' => $data,
            'count' => $count,
            'date' => $date,
            'totalWeight' => $totalWeight,
        ];

        return view($this->viewPath . 'logisticsDelivery', $response);
    }

    /**
     *  批量修改包裹物流方式 
     *
     *  @param $arr  packageId数组    $id     物流id
     *  @return redirect
     *
     */
    public function changeLogistics($arr, $id) 
    {
        $arr = explode(',', $arr);
        $name = UserModel::find(request()->user()->id)->name;
        foreach($arr as $packageId) {
            $model = $this->model->find($packageId);
            $from = base64_ecode(serialize($model));
            if (!$model) {
                continue;
            }
            if(in_array($model->status, ['PICKING', 'PACKED', 'SHIPPED'])) {
                continue;
            }
            $model->update(['logistics_id' => $id]);
            $to = base64_decode(serialize($model));
            $this->eventLog($name, '改变物流方式', $to, $from);
        }

        return redirect($this->mainIndex);
    }

    /**
     * 批量删除包裹 
     *
     *  @param $array  packageId
     *  @return redirect
     *
     */
    public function removePackages($arr)
    {
        $arr = explode(',', $arr);
        foreach($arr as $packageId) {
            $model = $this->model->find($packageId);
            if (!$model) {
                continue;
            }
            if(in_array($model->status, ['PICKING', 'PACKED', 'SHIPPED'])) {
                continue;
            }
            foreach($model->items as $packageItem) {
                $packageItem->delete();
            }
            if($model->order->packages->count() == 1) {
                $model->order->update(['status' => 'CANCEL']);
            }
            $model->delete();
        }

        return redirect($this->mainIndex);
    }

    /**
     * 批量清空物流方式 
     *
     *  @param arr packageId 数组
     *  @return redirect
     *
     */
    public function removeLogistics($arr) 
    {
        $arr = explode(',', $arr);
        $name = UserModel::find(request()->user()->id)->name;
        foreach($arr as $packageId) {
            $model = $this->model->find($packageId);
            $from = base64_encode(serialize($model));
            if (!$model) {
                continue;
            }
            if(in_array($model->status, ['PICKING', 'PACKED', 'SHIPPED'])) {
                continue;
            }
            $model->update(['tracking_no' => '']);
            $to = base64_encode(serialize($model));
            $this->eventLog($name, '清空物流方式', $to, $from);
        }

        return redirect($this->mainIndex);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function edit($id)
    // {
    //     $model = $this->model->find($id);
    //     if (!$model) {
    //         return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
    //     }
    //     $response = [
    //         'metas' => $this->metas(__FUNCTION__),
    //         'model' => $model,
    //         'logisticses' => LogisticsModel::all(),
    //         'status' => config('package'),
    //     ];

    //     return view($this->viewPath . 'edit', $response);
    // }

    public function flow()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, 'Flow'),
            'packageNum' => $this->model->where('status', 'NEED')->count(),
            'ordernum' => OrderModel::where('status', 'PREPARED')->get()->filter(function($single){return $single->packages()->count() == 0;})->count(),
            'assignNum' => $this->model->where(['status' => 'WAITASSIGN'])->count(),
            'placeNum' => $this->model->whereIn('status', ['ASSIGNED', 'TRACKINGFAIL'])->where('is_auto', '1')->count(),
            'manualShip' => $this->model->where(['is_auto' => '0', 'status' => 'ASSIGNED'])->count(),
            'pickNum' => $this->model->where(['status' => 'PROCESSING', 'is_auto' => '1'])->count(),
            'printNum' => PickListModel::where('status', 'NONE')->count(),
            'singlePack' => PickListModel::where('type', 'SINGLE')->whereIn('status',
                ['PACKAGEING', 'PICKING'])->count(),
            'singleMultiPack' => PickListModel::where('type', 'SINGLEMULTI')->whereIn('status',
                ['PACKAGEING', 'PICKING'])->count(),
            'multiInbox' => PickListModel::where('type', 'MULTI')->where('status', 'PICKING')->count(),
            'multiPack' => PickListModel::where('type', 'MULTI')->whereIn('status', ['INBOXED', 'PACKAGEING'])->count(),
            'packageShipping' => $this->model->where('status', 'PACKED')->count(),
            'packageException' => $this->model->where('status', 'ERROR')->count(),
            'assignFailed' => $this->model->where('status', 'ASSIGNFAILED')->count(),
        ];

        return view($this->viewPath . 'flow', $response);
    }

    public function autoFailAssignLogistics()
    {
        $packages = $this->model->where('status', 'ASSIGNFAILED')->get();
        foreach($packages as $package) {
            $job = new AssignLogistics($package);
            $job = $job->onQueue('assignLogistics');
            $this->dispatch($job);
        }

        return redirect(route('package.flow'))->with('alert', $this->alert('success', $packages->count().'个包裹放入队列'));
    }

    public function allocateLogistics($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '分配物流方式'),
            'logisticses' => LogisticsModel::all(),
            'id' => $id,
        ];

        return view($this->viewPath . 'allocateLogistics', $response);
    }

    public function downloadFee()
    {
        $rows[] = [
            'package_id' => '',
            'cost' => '',
        ];
        $name = 'Fee';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function returnGoods()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::all(),
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath.'returnGoods', $response);
    }

    public function processReturnGoods()
    {
        $file = request()->file('returnFile');
        $arr = $this->model->processGoods($file);
        $errors = [];
        $warehouse_id = request('stock_warehouse_id');
        if(request('type') == 'only') {
            DB::beginTransaction();
            try {
                foreach($arr as $key => $tracking_no) {
                    $model = $this->model->where('tracking_no', $tracking_no)->first();
                    if(!$model) {
                        $errors[$key]['id'] = $tracking_no;
                        $errors[$key]['remark'] = '对应包裹不存在';
                        continue;
                    }
                    foreach($model->items as $packageItem) {
                        $stock = StockModel::where(['item_id' => $packageItem->item_id, 'warehouse_id' => $warehouse_id])->first();
                        if(!$stock) {
                            $errors[$key]['id'] = $tracking_no;
                            $errors[$key]['remark'] = '仓库对应库位有问题';
                            continue;
                        }
                        $packageItem->item->in($stock->warehouse_position_id, $packageItem->quantity, $packageItem->quantity * $packageItem->item->cost, 'CANCEL', $model->id);
                    }
                    $model->delete();
                }
                if(count($errors)) {
                    throw new Exception('导入数据有问题');
                }
            } catch(Exception $e) {
                DB::rollback();
            }
            DB::commit();
        } else {
            DB::beginTransaction();
            try {
                foreach($arr as $key => $tracking_no) {
                    $model = $this->model->where('tracking_no', $tracking_no)->first();
                    if(!$model) {
                        $errors[$key]['id'] = $tracking_no;
                        $errors[$key]['remark'] = '对应包裹不存在';
                        continue;
                    }
                    foreach($model->items as $packageItem) {
                        $stock = StockModel::where(['item_id' => $packageItem->item_id, 'warehouse_id' => $warehouse_id])->first();
                        if(!$stock) {
                            $errors[$key]['id'] = $tracking_no;
                            $errors[$key]['remark'] = '仓库对应库位有问题'; 
                            continue;
                        }
                        $packageItem->item->in($stock->warehouse_position_id, $packageItem->quantity, $packageItem->quantity * $packageItem->item->cost, 'CANCEL', $model->id);
                    }
                    if(request('trackingNo') == 'on') {
                        $model->update(['tracking_no' => '']);
                    }
                    if(request('logistics_id') != 'auto') {
                        $model->update(['logistics_id' => request('logistics_id'), 'status' => 'ASSIGNED']);
                    } else {
                        $model->update(['status' => 'NEW']);
                    }
                    $model->update(['warehouse_id' => request('from_warheouse_id')]);
                }
                if(count($errors)) {
                    throw new Exception('导入数据有问题');
                }
            } catch(Exception $e) {
                DB::rollback();
            }
            DB::commit();
        }

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'returnErrors' => $errors,
        ];

        return view($this->viewPath.'returnErrors', $response);
        
    }

    public function returnSplitPackage()
    {
        $quantity = request('quantity');
        $id = request('id');
        $model = $this->model->find($id);
        if(!$model) {
            return json_encode(false);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'quantity' => $quantity,
        ];

        return view($this->viewPath.'splitPackage', $response);
    }
    
    public function editTrackingNo($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__, '修改追踪号'),
            'model' => $model,
        ];

        return view($this->viewPath.'editTrackingNo', $response);
    }

    public function implodePackage($tmp)
    {
        $arr = [];
        $buf = explode(',', $tmp);
        $name = UserModel::find(request()->user()->id)->name;
        $orderId = $this->model->find($buf[0])->order->id;
        foreach($buf as $key => $packageId) {
            $model = $this->model->find($packageId);
            if (!$model) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
            }
            if($model->order->id != $orderId) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '要合并的包裹不是来自于一个订单'));
            }
        }

        foreach($buf as $key => $packageId) {
            $model = $this->model->find($packageId);
            if (!$model) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
            }
            foreach($model->items as $packageItem) {
                if(!array_key_exists($packageItem->item_id, $arr)) {
                    $arr[$packageItem->item_id]['quantity'] = $packageItem->quantity;
                    $arr[$packageItem->item_id]['warehouse_position_id'] = $packageItem->warehouse_position_id;
                    $arr[$packageItem->item_id]['order_item_id'] = $packageItem->order_item_id;
                    $arr[$packageItem->item_id]['remark'] = $packageItem->remark;
                    $arr[$packageItem->item_id]['is_mark'] = $packageItem->is_mark;
                    $arr[$packageItem->item_id]['is_upload'] = $packageItem->is_upload;
                } else {
                    $arr[$packageItem->item_id]['quantity'] += $packageItem->quantity;
                }
                $packageItem->delete();
            }
            if($key) {
                $model->delete();
            }
        }
        $model = $this->model->with('items')->find($buf[0]);
        $from = base64_encode(serialize($model));
        $model->update(['status' => 'NEW']);
        $model->order->update(['status' => 'REVIEW']);
        if($model) {
            foreach($arr as $itemId => $info) {
                $info['item_id'] = $itemId;
                $model->items()->create($info);
            }
        }
        $to = base64_encode(serialize($model));
        $this->eventLog($name, '合并包裹', $to, $from);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '合并成功.'));
    }

    public function editTrackStore($id)
    {
        $model = $this->model->with('items')->find($id);
        $name = UserModel::find(request()->user()->id)->name;
        $from = base64_encode(serialize($model));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->update(['tracking_no' => request('tracking_no')]);
        $to = base64_encode(serialize($model));
        $this->eventLog($name, '修改追踪号', $to, $from);
        return redirect($this->mainIndex);
    }

    public function actSplitPackage($arr, $id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $name = UserModel::find(request()->user()->id)->name;
        $tmp = $this->processArr($arr, $model);
        sort($tmp);
        if(count($tmp) == 1) {
            return redirect($this->mainIndex)->with('alert', $this->alert('warning', $this->mainTitle . '拆后包裹个数还是1.'));
        } else {
            foreach($model->items as $item) {
                $item->delete();
            }
            foreach($tmp as $packageId => $info) {
                if(!$packageId) {
                    $from = base64_encode(serialize($model));
                    foreach($info as $itemId => $packageItem) {
                        $packageItem['item_id'] = $itemId;
                        $model->items()->create($packageItem);
                    }
                    $model->update(['status' => 'NEW']);
                    $to = base64_encode(serialize($model));
                    $this->eventLog($name, '拆分包裹', $to, $from);
                    $model->order->update(['status' => 'REVIEW']);
                } else {
                    $newPackage = $this->model->create($model->toArray());
                    $to = base64_encode(serialize($newPackage));
                    foreach($info as $itemId => $packageItem) {
                        $packageItem['item_id'] = $itemId;
                        $newPackage->items()->create($packageItem);
                    }
                    $newPackage->update(['status' => 'NEW']);
                    $this->eventLog($name, '拆分包裹', $to);
                    $newPackage->order->update(['status' => 'REVIEW']);
                } 
            }
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '包裹拆分成功.'));

    }

    public function processArr($arr, $model)
    {
        $tmp = [];
        foreach(explode(',', $arr) as $key => $value) {
            $buf = explode('.', $value);
            if(!array_key_exists($buf[0], $tmp)) {
                $tmp[$buf[0]] = [];
            }
            if(!array_key_exists($buf[1], $tmp[$buf[0]])) {
                $tmp[$buf[0]][$buf[1]]['quantity'] = 0;
                $item = $model->items()->where('item_id', $buf[1])->first();
                if($item) {
                    $tmp[$buf[0]][$buf[1]]['warehouse_position_id'] = $item->warehouse_position_id;
                    $tmp[$buf[0]][$buf[1]]['order_item_id'] = $item->order_item_id;
                    $tmp[$buf[0]][$buf[1]]['remark'] = $item->remark;
                    $tmp[$buf[0]][$buf[1]]['is_remark'] = $item->is_remark;
                    $tmp[$buf[0]][$buf[1]]['is_upload'] = $item->is_upload;
                }
            }
            $tmp[$buf[0]][$buf[1]]['quantity'] += 1;
        }

        return $tmp;
    }

    public function downloadType()
    {
        $rows[] = [
            'package_id' => '',
            'logistics_id' => '',
            'tracking_no' => '',
        ];
        $name = 'returnTrack';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function downloadTrackingNo()
    {
        $rows[] = [
            'package_id' => '',
            'tracking_no' => '',
        ];
        $name = 'editTrackingNo';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function forceOutPackage()
    {
        $package_id = trim(request('package_id'));
        $name = UserModel::find(request()->user()->id)->name;
        $package = $this->model->with('items')->find($package_id);
        $from = base64_decode(serialize($package));
        if (!$package) {
            return json_encode(false);
        }
        $items = $package->items();
        foreach ($items as $item) {
            $item->update(['picked_quantity' => $item->quantity]);
            $item->item->out($item->warehouse_position_id, $item->quantity, 'PACKAGE', $package->id);
        }
        $package->update(['status' => 'PACKED']);
        $to = base64_decode(serialize($package));
        $this->eventLog($name, '强制出库', $to, $from);

        return json_encode(true);
    }

    public function multiPackage()
    {
        $package_id = trim(request('package_id'));
        $package = $this->model->find($package_id);
        if (!$package) {
            return json_encode(false);
        }
        $order = $package->order;
        if($order->status == 'REVIEW') {
            $package->update(['status' => 'ERROR']);
            return json_encode(false);
        }
        $items = $package->items;
        foreach ($items as $item) {
            $item->update(['picked_quantity' => $item->quantity]);
        }
        $package->update(['status' => 'PACKED']);
        DB::beginTransaction();
        try {
            foreach($package->items as $packageItem) {
                $packageItem->item->holdOut($packageItem->warehouse_position_id,
                                            $packageItem->quantity,
                                            'PACKAGE',
                                            $packageItem->id);
                $packageItem->orderItem->update(['status' => 'SHIPPED']);
            }
        } catch (Exception $e) {
            DB::rollback();
            return json_encode('unhold');
        }
        DB::commit();       

        return json_encode(true);
    }

    public function storeAllocateLogistics($id)
    {
        $model = $this->model->find($id);
        $name = UserModel::find(request()->user()->id)->name;
        $from = base64_encode(serialize($model));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logistics = LogisticsModel::find(request('logistics_id'));
        if ($logistics->docking == '手工发货') {
            $model->update(['is_auto' => '0']);
        }
        $model->update(['logistics_id' => request('logistics_id'), 'status' => 'ASSIGNED']);
        $to = base64_encode(serialize($model));
        $this->eventLog($name, '拆分包裹', $to, $from);

        return redirect($this->mainIndex);
    }

    public function ajaxQuantityProcess()
    {
        $buf = request()->input('buf');
        foreach ($buf as $v) {
            $model = $this->model->find($v);
            $model->update(['status' => 'SHIPPED']);
        }

        return json_encode(true);
    }

    /**
     * 添加未处理包裹至分配物流方式队列
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignLogistics()
    {
        $len = 1000;
        $start = 0;
        $packages = $this->model
            ->where('status', 'WAITASSIGN')
            ->where('is_auto', '1')
            ->skip($start)->take($len)->get();
        while ($packages->count()) {
            foreach ($packages as $package) {
                $job = new AssignLogistics($package);
                $job = $job->onQueue('assignLogistics');
                $this->dispatch($job);
            }
            $start += $len;
            unset($packages);
            $packages = $this->model
                ->where('status', 'NEW')
                ->where('is_auto', '1')
                ->skip($start)->take($len)->get();
        }
        return redirect(route('dashboard.index'))->with('alert', $this->alert('success', '添加至 [ASSIGN LOGISTICS] 队列成功'));
    }

    /**
     * 添加已分配物流方式包裹至物流下单队列
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeLogistics()
    {
        $len = 1000;
        $start = 0;
        $packages = $this->model
            ->whereIn('status', ['ASSIGNED','TRACKINGFAILED'])
            ->where('is_auto', '1')
            ->skip($start)->take($len)->get();
        while ($packages->count()) {
            foreach ($packages as $package) {
                $orderRate = $package->order->calculateProfitProcess();
                if ($orderRate > 0) {
                    $job = new PlaceLogistics($package);
                    $job = $job->onQueue('placeLogistics');
                    $this->dispatch($job);
                }
            }
            $start += $len;
            unset($packages);
            $packages = $this->model
                ->where('status', 'ASSIGNED')
                ->where('is_auto', '1')
                ->skip($start)->take($len)->get();
        }
        return redirect(route('dashboard.index'))->with('alert', $this->alert('success', '添加至 [PLACE LOGISTICS] 队列成功'));
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function store()
    {
        request()->flash();
//        $order = OrderModel::find(request()->input('order_id'));
        $order = OrderModel::where('ordernum', '=', request()->input('ordernum'))->first();
        if ($order) {
            $this->validate(request(), $this->model->rules('create'));
            if ($order->createPackage()) {
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '包裹创建成功'));
            } else {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '包裹创建失败.'));
            }
        } else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '订单不存在'));
        }
    }

    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
        $arr = explode(' ', request('name'));
        $model->update([
            'shipping_firstname' => array_key_exists('0', $arr) ? $arr['0'] : '',
            'shipping_lastname' => array_key_exists('1', $arr) ? $arr['1'] : ''
        ]);

        return redirect($this->mainIndex);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        foreach ($model->items as $packageItem) {
            $stockout = $packageItem->stockout;
            $stock = StockModel::find($stockout->stock_id);
            $stock->in($stockout->quantity, $stockout->amount, 'PACKAGE_CANCEL');
            $packageItem->delete();
            $stockout->delete();
        }
        $model->destroy($id);
        return redirect($this->mainIndex);
    }

    public function ajaxReturnPackageId()
    {
        $trackno = request('trackno');
        if ($trackno) {
            $model = $this->model->where(['tracking_no' => $trackno])->first();
            if ($model) {
                return json_encode($model->id);
            }
        }
        return json_encode(false);
    }

    public function ajaxUpdatePackageLogistics()
    {
        $package_id = request('package_id');
        $trackno = request('trackno');
        $logistics_id = request('logistics_id');
        $model = '';
        if ($package_id) {
            $model = $this->model->find($package_id);
        } else {
            $model = $this->model->where(['tracking_no' => $trackno])->first();
        }
        if ($model) {
            $model->update(['logistics_id' => $logistics_id]);
            return json_encode($model->id);
        }

        return json_encode(false);
    }

    public function ajaxGetOrder()
    {
        if (request()->ajax()) {
            $order = OrderModel::where('ordernum', request()->input('ordernum'))->first();
            if ($order) {
                $response = [
                    'order' => $order,
                ];
                return view($this->viewPath . 'ajax.order', $response);
            }
        }
        return 'error';
    }

    public function manualShipping()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses' => LogisticsModel::all(),
            'packages' => $this->model->where(['status' => 'ASSIGNED', 'is_auto' => '0'])->paginate(15),
        ];

        return view($this->viewPath . 'manualShipping', $response);
    }

    public function manualLogistics()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses' => LogisticsModel::all(),
            'packages' => $this->model->where(['status' => 'ASSIGNFAILED', 'is_auto' => '1'])->paginate(15),
        ];

        return view($this->viewPath . 'manualLogistics', $response);
    }

    public function setManualLogistics()
    {
        $id = request('id');
        $logistics_id = request('logistics');
        $model = $this->model->find($id);
        if (!$model) {
            return json_encode(false);
        }
        $logistics = LogisticsModel::find($logistics_id);
        $is_auto = ($logistics->docking == 'MANUAL' ? '0' : '1');
        $model->update(['logistics_id' => $logistics_id, 'status' => 'ASSIGNED', 'is_auto' => $is_auto]);
        $orderRate = $model->order->calculateProfitProcess();
        if($orderRate > 0) {
            if($is_auto) {
                $job = new PlaceLogistics($model);
                $job = $job->onQueue('placeLogistics');
                $this->dispatch($job);
            } 
        } else {
            $this->model->order->OrderCancle();
        }
        
        return json_encode(true);
    }

    public function ajaxPackageSend()
    {
        $id = request()->input('id');
        $package = $this->model->find($id);
        foreach ($package->items as $packageItem) {
            $item = ItemModel::find($packageItem->item_id);
            $item->unhold($packageItem->warehouse_position_id, $packageItem->picked_quantity);
            $item->out($packageItem->warehouse_position_id, $packageItem->picked_quantity);
        }
        $package->status = 'SHIPPED';
        $package->save();
        echo json_encode('success');
    }

    /**
     * 撤销包装的单个package
     *
     * @param none
     * @return json
     *
     */
    public function ctrlZ()
    {
        $packageId = request('packageId');
        $package = $this->model->find($packageId);
        $package->status = 'PICKING';
        $package->save();
        $items = $package->items;
        foreach ($items as $item) {
            $item->picked_quantity = 0;
            $item->save();
            $item->item->in($item->warehouse_position_id, $item->quantity, $item->quantity * $item->item->cost, 'CANCEL', $item->id);
            $item->item->hold($item->warehouse_position_id, $item->quantity);
        }
        return json_encode(true);
    }

    /**
     * 跳转发货页面
     *
     * @param none
     * @return view
     *
     */
    public function shipping()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => LogisticsModel::all(),
        ];

        return view($this->viewPath . 'shipping', $response);
    }

    /**
     * 导出手工发货包裹信息
     *
     * @param none
     * @return csv
     *
     */
    public function exportManualPackage()
    {
        $str = request()->input('arr');
        $arr = explode('|', $str);
        $rows = '';
        foreach ($arr as $id) {
            $package = $this->model->find($id);
            if ($package->is_auto || (!$package->is_auto && $package->status != 'PROCESSING')) {
                continue;
            }
            $package->update(['status' => 'PACKED', 'shipper_id' => '2', 'shipped_at' => date('Y-m-d G:i:s', time())]);
            foreach ($package->items as $item) {
                $rows[] = [
                    'package  ID' => $id,
                    'sku' => ItemModel::find($item->item_id)->sku,
                    'warehouse_position' => PositionModel::find($item->warehouse_position_id)->name,
                    'quantity' => $item->quantity,
                ];
            }
        }
        $name = 'ManualPackage';
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '手工发货包裹';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function bagInfo()
    {
        $trackno = request('trackno');
        $model = $this->model->where('tracking_no', $trackno)->first();
        if(!$model) {
            return json_encode(false);
        }
        $number = NumberModel::first();
        if(!count($number)) {
            $number = NumberModel::create(['number' => 1]);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $model->logistics ? $model->logistics->code : '',
            'number' => 'S'.substr($number->number+100000000, 1),
        ];
        $number->update(['number' => $number->number + 1]);

        return view($this->viewPath.'bagInfo', $response);
    }

    /**
     * 执行发货
     *
     * @param none
     * @return json
     *
     */
    public function ajaxShippingExec()
    {
        $track_no = request()->input('trackno');
        $weight = request()->input('weight');
        $logistic_id = request()->input('logistic_id');
        $package = PackageModel::where(['tracking_no' => $track_no, 'status' => 'PACKED'])->first();
        if (!$package) {
            return json_encode('error');
        }
        $name = UserModel::find(request()->user()->id)->name;
        $from = base64_encode(serialize($package));
        if($weight == '0') {
            $package->update([
                'shipped_at' => date('Y-m-d h:i:s', time()),
                'shipper_id' => request()->user()->id,
                'status' => 'SHIPPED',
            ]);
        } else {
            $package->update([
                'shipped_at' => date('Y-m-d h:i:s', time()),
                'shipper_id' => request()->user()->id,
                'actual_weight' => $weight,
                'status' => 'SHIPPED',
            ]);
        }
        $order = $package->order;
        $buf = 1;
        foreach($order->packages as $childPackage) {
            if($childPackage->status != 'SHIPPED') {
                $buf = 0;
            }
        }

        if($buf) {
            foreach($package->items as $packageItem) {
                $packageItem->orderItem->update(['status' => 'SHIPPED']);
            }
            $order->update(['status' => 'SHIPPED']);
        } else {
            $order->update(['status' => 'PARTIAL']);
        }

        $to = base64_encode(serialize($package));
        $this->eventLog($name, '发货', $to, $from);
        if (!in_array($package->logistics_id, $logistic_id)) {
            return json_encode('logistic_error');
        }

        return json_encode('success');
    }

    /**
     * 跳转发货统计页面
     *
     * @param none
     * @return view
     *
     */
    public function shippingStatistics()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '发货统计'),
        ];

        return view($this->viewPath . 'statistics', $response);
    }

    /**
     * 导出数据 according to start_time end_time
     *
     * @param none
     * @return none
     *
     */
    public function exportData()
    {
        $start_time = request()->input('start_time');
        $end_time = request()->input('end_time');
        $packages = PackageModel::whereBetween('shipped_at', [$start_time, $end_time])->get();
        $this->model->exportData($packages);
    }

    /**
     * 跳转excel页面
     *
     * @param none
     * @return view
     *
     */
    public function returnTrackno()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '导入trackno'),
            'action' => route('package.excelProcess'),
        ];

        return view($this->viewPath . 'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcess()
    {
        if (request()->hasFile('excel')) {
            $file = request()->file('excel');
            $errors = $this->model->excelProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__, '导入结果'),
                'errors' => $errors,
            ];

            return view($this->viewPath . 'excelResult', $response);
        }
    }

    /**
     * 跳转excel页面
     *
     * @param none
     * @return view
     *
     */
    public function returnFee()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'action' => route('package.excelProcessFee', ['type' => request('type')]),
            'type' => request('type') ? request('type') : '',
        ];

        return view($this->viewPath . 'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcessFee($type)
    {
        if (request()->hasFile('excel')) {
            $file = request()->file('excel');
            $errors = $this->model->excelProcessFee($file, $type);
            $response = [
                'metas' => $this->metas(__FUNCTION__, '导入结果'),
                'errors' => $errors,
                'type' => $type,
            ];

            return view($this->viewPath . 'excelFeeResult', $response);
        }
    }

    /**
     * 包裹面单信息
     */
    public function templateMsg($id)
    {
        $model = $this->model->find($id);
        if ($model->logistics) {
            $view = $model->logistics->template;
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'model' => $model,
            ];

            return view('logistics.template.tpl.' . explode('.', $view->view)[0], $response);
        }
        
        return false;
    }

}