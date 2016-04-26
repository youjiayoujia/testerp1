<?php
/**
 * 包裹控制器
 *
 * 2016-03-09
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use Excel;
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
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '包裹创建失败,库存不足. 已生成订单需求.'));
            }
        } else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '订单不存在'));
        }
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

    public function manualLogistic($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logistics' => LogisticsModel::all(),
        ];

        return view($this->viewPath . 'fee', $response);
    }

    public function feeStore()
    {
        $model = $this->model->find(request()->input('id'));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->update(request()->all());
        $model->status = 'SHIPPED';
        $model->save();

        return redirect($this->mainIndex);
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

    public function exportManualPackage()
    {
        $str = request()->input('arr');
        $arr = explode('|', $str);
        $rows = '';
        foreach($arr as $id) {
            $package = $this->model->find($id);
            if($package->is_auto || $package->status == 'PROCESSING') {
                continue;
            }
            $package->update(['status' => 'PACKED', 'shipper_id' => '2', 'shipped_at' => date('Y-m-d G:i:s', time())]);
            foreach($package->items as $item) {
                $rows[] = [
                    'package  ID' => $id,
                    'sku' => ItemModel::find($item->item_id)->sku,
                    'warehouse_position' => PositionModel::find($item->warehouse_position_id)->name,
                    'quantity' => $item->quantity,
                    'created_at' => $item->created_at
                ];
            }
        }
        $name = 'ManualPackage';
        Excel::create($name, function($excel) use ($rows){
            $nameSheet='手工发货包裹';
            $excel->sheet($nameSheet, function($sheet) use ($rows){
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
        $package->update(['status' => 'SHIPPED', 'shipped_at' => date('Y-m-d h:i:s', time()), 'shipper_id' => '2', 'actual_weight' => $weight]);
        return json_encode(true);
    }

    public function shippingStatistics()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath . 'statistics', $response);
    }

    public function exportData()
    {
        $start_time = request()->input('start_time');
        $end_time = request()->input('end_time');
        $packages = PackageModel::whereBetween('shipped_at', [$start_time, $end_time])->get();
        $this->model->exportData($packages);
    }

    public function returnTrackno()
    {

    }

    /**
     * excel 导入数据
     *
     * @param
     *
     */
    public function importByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcess()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $errors = $this->model->excelProcess($file);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];
            return view($this->viewPath.'excelResult', $response);
        }
    }
    
    public function returnFee()
    {

    }
}