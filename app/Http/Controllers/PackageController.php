<?php
/**
 * 包裹控制器
 *
 * 2016-03-09
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\ItemModel;
use App\Models\LogisticsModel;

class PackageController extends Controller
{
    public function __construct(PackageModel $package)
    {
        $this->model = $package;
        $this->mainIndex = route('package.index');
        $this->mainTitle = '包裹';
        $this->viewPath = 'package.';
    }

    public function ajaxGetOrder()
    {
        if (request()->ajax()) {
            $order = OrderModel::where('order_number', request()->input('ordernum'))->first();
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
        foreach($package->listItemPackage as $itemPackage)
        {
            $picklistItem = $itemPackage->picklistItem;
            $item = ItemModel::find($picklistItem->item_id);
            $item->unhold($picklistItem->warehouse_position_id, $picklistItem->quantity);
            $item->out($picklistItem->warehouse_position_id, $picklistItem->quantity);
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

        return view($this->viewPath.'fee', $response);
    }

    public function feeStore()
    {
        $model = $this->model->find(request()->input('id'));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->logistic_id = request()->input('logistic_id');
        $model->status = 'SHIPPED';
        $model->save();
        $model->manualLogistic()->create(['logistic_code'=>request()->input('logistic_code'), 'fee'=>request()->input('fee'), 'remark'=>request()->input('remark')]);

        return redirect($this->mainIndex);
    }
}