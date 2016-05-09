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
            'packageNum' => OrderModel::where('active', 'NORMAL')
                ->whereIn('status', ['PREPARED', 'NEED'])->count(),
            'assignNum' => $this->model->where('status', 'NEW')->count(),
            'placeNum' => $this->model->where('status', 'ASSIGNED')->count(),
            'pickNum' => $this->model->where(['status' => 'PROCESSING', 'is_auto' => '1'])->count(),
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

    public function storeAllocateLogistics($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->update(['logistics_id' => request('logistics_id'), 'status' => 'ASSIGNED']);

        return redirect($this->mainIndex);
    }

    public function doPackage()
    {
        $begin = microtime(true);
        $orders = OrderModel::where('active', 'NORMAL')
            ->whereIn('status', ['PREPARED', 'NEED'])
            ->orderBy('package_times', 'desc')
            ->get();
        foreach ($orders as $order) {
            echo $order->id . '<br>';
            $order->createPackage();
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function assignLogistics()
    {
        $begin = microtime(true);
        $packages = PackageModel::where('status', 'NEW')->where('is_auto', '1')->get();
        foreach ($packages as $package) {
            echo $package->id . '<br>';
            $status = $package->assignLogistics();
            if (!$status) {
                $package->update(['status' => 'ASSIGNFAILED']);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function placeLogistics()
    {
        $begin = microtime(true);
        $packages = PackageModel::where('status', 'ASSIGNED')->where('is_auto', '1')->get();
        foreach ($packages as $package) {
            echo $package->id . '<br>';
            $package->placeLogistics();
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
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
        if ($package->logistics_id != $logistic_id) {
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
}