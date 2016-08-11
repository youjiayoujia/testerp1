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

    public $searchFields = ['code' => '物流方式简码', 'name' => '物流方式名称'];

    protected $fillable = [
        'id',
        'code',
        'name',
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
        'driver',
    ];

    public $rules = [
        'create' => [
            'code' => 'required',
            'name' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
            'driver' => 'required',
        ],
        'update' => [
            'code' => 'required',
            'name' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
            'driver' => 'required',
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

    public function channelName()
    {
        return $this->belongsToMany('App\Models\Logistics\ChannelNameModel', 'logistics_belongstos', 'logistics_id',
            'logistics_channel_id');
    }

    public function getApiConfigAttribute()
    {
        $config = [];
        $config['type'] = $this->type;

        $config['url'] = $this->supplier->url;
        $config['userId'] = $this->supplier->customer_id;
        $config['userPassword'] = $this->supplier->password;
        $config['key'] = $this->supplier->secret_key;

        $config['returnCompany'] = $this->emailTemplate->unit;
        $config['returnContact'] = $this->emailTemplate->sender;
        $config['returnPhone'] = $this->emailTemplate->phone;
        $config['returnAddress'] = $this->emailTemplate->address;
        $config['returnZipcode'] = $this->emailTemplate->zipcode;
        $config['returnCountry'] = $this->emailTemplate->country_code;
        $config['returnProvince'] = $this->emailTemplate->province;
        $config['returnCity'] = $this->emailTemplate->city;
        return $config;
    }

    public function getDockingNameAttribute()
    {
        $arr = config('logistics.docking');
        return $arr[$this->docking];
    }

    public function hasLimits($id)
    {
        $arr = explode(',', $this->limit);
        if (in_array($id, $arr)) {
            return true;
        }
        return false;
    }

    public function inType($id)
    {
        $multi = $this->channelName;
        foreach ($multi as $single) {
            if ($single->id == $id) {
                return true;
            }
        }
        return false;
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