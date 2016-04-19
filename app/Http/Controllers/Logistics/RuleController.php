<?php
/**
 * 物流分配规则控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午3:20
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\ItemModel;
use App\Models\Logistics\RuleModel;
use App\Models\CountryModel;
use App\Models\LogisticsModel;
use App\Models\OrderModel;
use App\Models\Package\ItemModel as packageItemModel;
use App\Models\PackageModel;

class RuleController extends Controller
{
    public function __construct(RuleModel $rule)
    {
        $this->model = $rule;
        $this->mainIndex = route('logisticsRule.index');
        $this->mainTitle = '物流分配规则';
        $this->viewPath = 'logistics.rule.';
    }

    /**
     * 新增
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses' => LogisticsModel::all(),
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'name']),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $selectedCountry = $model->country;
        $selectedCountries = explode(",",$selectedCountry);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'name']),
            'selectedCountries' => $selectedCountries,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 物流规则
     * @param $packageId
     */
    public function logisticsRule($packageId)
    {
        $package = PackageModel::where(['id' => $packageId])->get();
        $weight = $package['weight'];
        $orderId = $package['order_id'];
        $packageItem = packageItemModel::where(['package_id' => $packageId])->get();
        $itemId = $packageItem['item_id'];
        $item = ItemModel::where(['id' => $itemId])->get();
        $order = OrderModel::where(['id' => $orderId])->get();
        $amount = $order['amount'];
        RuleModel::where('weight_from', '<=', $weight)->where($weight, '<=', 'weight_to')->where($amount, '<=', 'order_amount')->get();

    }

}