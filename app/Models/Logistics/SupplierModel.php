<?php
/**
 * 物流商模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:11
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class SupplierModel extends BaseModel
{
    protected $table = 'logistics_suppliers';

    public $searchFields = ['name' => '物流商名称', 'customer_id' => '客户ID'];

    protected $fillable = [
        'id',
        'name',
        'customer_id',
        'secret_key',
        'is_api',
        'client_manager',
        'manager_tel',
        'technician',
        'technician_tel',
        'remark',
        'bank',
        'card_number',
        'url',
        'password',
        'customer_service_name',
        'customer_service_qq',
        'customer_service_tel',
        'finance_name',
        'finance_qq',
        'finance_tel',
        'driver',
        'driver_tel',
        'logistics_collection_info_id',
        'credentials',
    ];

    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_suppliers,name',
            'customer_id' => 'required',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
            'url' => 'required',
            'password' => 'required',
            'customer_service_name' => 'required',
            'customer_service_qq' => 'required',
            'customer_service_tel' => 'required',
            'finance_name' => 'required',
            'finance_qq' => 'required',
            'finance_tel' => 'required',
            'driver' => 'required',
            'driver_tel' => 'required',
            'logistics_collection_info_id' => 'required',
            'credentials' => 'required',
        ],
        'update' => [
            'name' => 'required|unique:logistics_suppliers,name,{id}',
            'customer_id' => 'required',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
            'url' => 'required',
            'password' => 'required',
            'customer_service_name' => 'required',
            'customer_service_qq' => 'required',
            'customer_service_tel' => 'required',
            'finance_name' => 'required',
            'finance_qq' => 'required',
            'finance_tel' => 'required',
            'driver' => 'required',
            'driver_tel' => 'required',
            'logistics_collection_info_id' => 'required',
            'credentials' => 'required',
        ],
    ];

    public function collectionInfo()
    {
        return $this->belongsTo('App\Models\Logistics\CollectionInfoModel', 'logistics_collection_info_id', 'id');
    }

}