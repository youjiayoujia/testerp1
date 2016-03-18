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
}