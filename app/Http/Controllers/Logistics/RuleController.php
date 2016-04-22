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
use App\Models\ProductModel;

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
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'abbreviation']),
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
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'abbreviation']),
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
        $weight = PackageModel::where(['id' => $packageId])->get(['weight']);
        $shippingCountry = PackageModel::where(['id' => $packageId])->get(['shipping_country']);
        $orderId = PackageModel::where(['id' => $packageId])->get(['order_id']);
        $amount = OrderModel::where(['id' => $orderId])->get(['amount']);
        $amountShipping = OrderModel::where(['id' => $orderId])->get(['amount_shipping']);
        $celeAdmin = OrderModel::where(['id' => $orderId])->get(['cele_admin']);
        if($amount > $amountShipping && $amount > 0.1 && $celeAdmin == null) {
            $isClearance = 1;
        }else{
            $isClearance = 0;
        }
        $rules = RuleModel::where('weight_from', '<=', $weight)->where($weight, '<=', 'weight_to')->where($amount, '<=', 'order_amount')->where(['is_clearance' => $isClearance])->orderBy('priority', 'desc')->get();
        foreach($rules as $rule) {
            $logisticsId = $rule['type_id'];
            $limit = LogisticsModel::where(['id' => $logisticsId])->get(['limit']);
            $packageItems = packageItemModel::where(['package_id' => $packageId])->get();
            $packageLimits = [];
            foreach($packageItems as $packageItem) {
                $itemId = $packageItem['item_id'];
                $productId = ItemModel::where(['id' => $itemId])->get(['product_id']);
                $packageLimit = ProductModel::where(['id' => $productId])->get(['package_limit']);
                $packageLimits = array_merge($packageLimits, explode(",", $packageLimit));
            }
            if(count(array_intersect(array($shippingCountry), explode(",", $rule['country']))) == 1 && count(array_intersect($packageLimits, explode(",", $limit))) == 0) {
                $model = PackageModel::where(['id' => $packageId])->first();
                $model->update(['logistics_id' => $logisticsId]);
                break;
            }
        }
    }

}