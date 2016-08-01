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

class PackageController extends Controller
{
    public function __construct(PackageModel $package)
    {
        $this->model = $package;
        $this->mainIndex = route('package.index');
        $this->mainTitle = '包裹';
        $this->viewPath = 'package.';
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
            'status' => config('package'),
        ];

        return view($this->viewPath . 'edit', $response);
    }

    public function flow()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, 'Flow'),
            'packageNum' => $this->model->whereIn('status', ['NEED', 'NEW'])->count(),
            'assignNum' => $this->model->where(['status' => 'WAITASSIGN'])->count(),
            'placeNum' => $this->model->whereIn('status', ['ASSIGNED', 'TRACKINGFAIL'])->count(),
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

    public function multiPackage()
    {
        $package_id = trim(request('package_id'));
        $package = $this->model->find($package_id);
        if (!$package) {
            return json_encode(false);
        }
        $items = $package->items;
        foreach ($items as $item) {
            $item->update(['picked_quantity' => $item->quantity]);
        }
        $package->update(['status' => 'PACKED']);

        return json_encode(true);
    }

    public function storeAllocateLogistics($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logistics = LogisticsModel::find(request('logistics_id'));
        if ($logistics->docking == '手工发货') {
            $model->update(['is_auto' => '0']);
        }
        $model->update(['logistics_id' => request('logistics_id'), 'status' => 'ASSIGNED']);

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
            ->where('status', 'NEW')
            ->where('is_auto', '1')
            ->skip($start)->take($len)->get();
        while ($packages->count()) {
            foreach ($packages as $package) {
                $job = new AssignLogistics($package);
                $job = $job->onQueue('assignLogistics');
                $this->dispatch($job);
            }
            $start += $len;
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
            ->where('status', 'ASSIGNED')
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
            $stock->in($stockout->quantity, $stockout->amount, 'PACKAGE_CANCLE');
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
            return json_encode(false);
        }
        if (!in_array($package->logistics_id, $logistic_id)) {
            return json_encode('logistic_error');
        }
        $package->update([
            'status' => 'SHIPPED',
            'shipped_at' => date('Y-m-d h:i:s', time()),
            'shipper_id' => '2',
            'actual_weight' => $weight
        ]);
        foreach ($package->items as $packageitem) {
            $packageitem->orderItem->update(['status' => 'SHIPPED']);
        }

        return json_encode(true);
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
            'metas' => $this->metas(__FUNCTION__, '导入fee'),
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