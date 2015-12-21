<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:25
 */

namespace App\Repositories;


use App\Base\BaseRepository;
use App\Models\LogisticsModel as Logistics;

class LogisticsRepository extends BaseRepository
{
    protected $searchFields = ['name'];
    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics,name',
            'customer_id' => 'required|numeric',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
        ],
        'update' => [
            'name' => 'required|unique:logistics,name,{id}',
            'customer_id' => 'required|numeric',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required|digits_between:8,11',
            'technician_tel' => 'required|digits_between:8,11',
        ],
    ];

    public function __construct(Logistics $logistics)
    {
        $this->model = $logistics;
    }

}