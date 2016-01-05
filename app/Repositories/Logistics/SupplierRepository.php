<?php
/**
 * 物流商库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:25
 */

namespace App\Repositories\Logistics;

use App\Base\BaseRepository;
use App\Models\Logistics\SupplierModel;

class SupplierRepository extends BaseRepository
{
    protected $searchFields = ['name', 'customer_id'];
    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_suppliers,name',
            'customer_id' => 'required|numeric',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
        ],
        'update' => [
            'name' => 'required|unique:logistics_suppliers,name,{id}',
            'customer_id' => 'required|numeric',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
        ],
    ];

    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
    }

}