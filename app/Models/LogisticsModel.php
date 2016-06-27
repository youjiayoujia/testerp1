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
use App\Models\Logistics\LimitsModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logisticses';

    public $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    protected $fillable = [
        'short_code',
        'logistics_type',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'url',
        'docking',
        'logistics_catalog_id',
        'logistics_email_template_id',
        'logistics_template_id',
        'pool_quantity',
        'is_enable',
        'limit',
    ];


    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
        ],
    ];

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

    public function codes()
    {
        return $this->hasMany('App\Models\Logistics\CodeModel', 'logistics_id');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\Logistics\CatalogModel', 'logistics_catalog_id', 'id');
    }

    public function emailTemplate()
    {
        return $this->belongsTo('App\Models\Logistics\EmailTemplateModel', 'logistics_email_template_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\Logistics\TemplateModel', 'logistics_template_id', 'id');
    }

    public function getDockingNameAttribute()
    {
        $arr = config('logistics.docking');
        return $arr[$this->docking];
    }

    /**
     * 物流商下单
     * todo:分方式下单
     */
    public function placeOrder($packageId)
    {
        $code = $this->codes->where('status', '0')->first();
        if ($code) {
            $code->update([
                'status' => 1,
                'package_id' => $packageId,
                'used_at' => date('y-m-d', time())
            ]);
            return $code->code;
        }
        return false;

    }

    /**
     * 遍历物流限制
     */
    public function limit($limit)
    {
        $str = '';
        foreach (explode(",", $limit) as $value) {
            $limits = LimitsModel::where(['id' => $value])->get();
            foreach ($limits as $limit) {
                $val = $limit['name'];
                $str = $str . $val . ',';
            }
        }
        return substr($str, 0, -1);
    }
}