<?php
/**
 * 物流分配规则模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午2:52
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;
use App\Models\CountryModel;
use App\Models\ItemModel;
use App\Models\LogisticsModel;
use App\Models\OrderModel;
use App\Models\PackageModel;
use App\Models\Package\ItemModel as packageItemModel;
use App\Models\ProductModel;

class RuleModel extends BaseModel
{
    protected $table = 'logistics_rules';

    public $searchFields = ['country', 'weight_from', 'weight_to', 'order_amount', 'is_clearance', 'priority', 'type_id'];

    protected $fillable = [
        'country',
        'weight_from',
        'weight_to',
        'order_amount',
        'is_clearance',
        'priority',
        'type_id',
    ];

    public $rules = [
        'create' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
        ],
        'update' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'type_id', 'id');
    }

    /**
     * 遍历国家
     */
    public function country($country)
    {
        $str = '';
        foreach(explode(",", $country) as $value) {
            $countries = CountryModel::where(['abbreviation' => $value])->get();
            foreach($countries as $country) {
                $val = $country['name'];
                $str = $str.$val.',';
            }
        }
        return substr($str, 0, -1);
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
                $url = LogisticsModel::where(['id' => $logisticsId])->get(['url']);
                $codeModel = CodeModel::where(['logistics_id' => $logisticsId, 'status' => 0])->first();
                $model->update(['logistics_id' => $logisticsId, 'tracking_link' => $url, 'tracking_no' => $codeModel['code']]);
                $codeModel->update(['status' => 1, 'package_id' => $packageId, 'used_at' => date('y-m-d', time())]);
                break;
            }
        }
    }

}