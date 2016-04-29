<?php
/**
 * 物流方式模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:13
 */

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\LimitsModel;
use App\Models\Logistics\RuleModel;
use App\Models\Package\ItemModel as packageItemModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logisticses';

    public $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    protected $fillable = [
        'short_code',
        'logistics_type',
        'species',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'url',
        'docking',
        'pool_quantity',
        'is_enable',
        'limit',
    ];


    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'is_enable' => 'required',
        ],
    ];


    /**
     * 批量倒入号码池
     *
     * @param $file 导入所需的Excel文件
     *
     */
    public function batchImport($file)
    {
        $filePath = '' . $file;
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Logistics\SupplierModel', 'logistics_supplier_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    public function logisticsLimit()
    {
        return $this->belongsTo('App\Models\Logistics\LimitsModel', 'limit', 'id');
    }

    /**
     * 遍历物流限制
     */
    public function limit($limit)
    {
        $str = '';
        foreach(explode(",", $limit) as $value) {
            $limits = LimitsModel::where(['id' => $value])->get();
            foreach($limits as $limit) {
                $val = $limit['name'];
                $str = $str.$val.',';
            }
        }
        return substr($str, 0, -1);
    }

    /**
     * 物流规则
     * @param $packageId
     */
    public function assign($packageId)
    {
        $weight = PackageModel::where(['id' => $packageId])->first()->weight;
        $shippingCountry = PackageModel::where(['id' => $packageId])->first()->shipping_country;
        $orderId = PackageModel::where(['id' => $packageId])->first()->order_id;
        $amount = OrderModel::where(['id' => $orderId])->first()->amount;
        $amountShipping = OrderModel::where(['id' => $orderId])->first()->amount_shipping;
        $celeAdmin = OrderModel::where(['id' => $orderId])->first()->cele_admin;
        if($amount > $amountShipping && $amount > 0.1 && $celeAdmin == null) {
            $isClearance = 1;
        }else{
            $isClearance = 0;
        }
        $rules = RuleModel::where('weight_from', '<=', $weight)->where('weight_to', '>=', $weight)->where('order_amount', '>=', $amount)->where(['is_clearance' => $isClearance])->orderBy('priority', 'desc')->get();
        foreach($rules as $rule) {
            $logisticsId = $rule['type_id'];
            $limit = LogisticsModel::where(['id' => $logisticsId])->first()->limit;
            $packageItems = packageItemModel::where(['package_id' => $packageId])->get();
            $packageLimits = [];
            foreach($packageItems as $packageItem) {
                $itemId = $packageItem['item_id'];
                $productId = ItemModel::where(['id' => $itemId])->first()->product_id;
                $packageLimit = ProductModel::where(['id' => $productId])->first()->package_limit;
                $packageLimits = array_merge($packageLimits, explode(",", $packageLimit));
            }
            if(count(array_intersect(array($shippingCountry), explode(",", $rule['country']))) == 1 && count(array_intersect($packageLimits, explode(",", $limit))) == 0) {
                $model = PackageModel::where(['id' => $packageId])->first();
                $url = LogisticsModel::where(['id' => $logisticsId])->first()->url;
                $codeModel = CodeModel::where(['logistics_id' => $logisticsId, 'status' => 0])->first();
                if(empty($model['logistics_id']) && empty($model['tracking_link']) && empty($model['tracking_no'])) {
                    $model->update(['logistics_id' => $logisticsId, 'tracking_link' => $url, 'tracking_no' => $codeModel['code']]);
                    $codeModel->update(['status' => 1, 'package_id' => $packageId, 'used_at' => date('y-m-d', time())]);
                }
                break;
            }
        }
    }

}