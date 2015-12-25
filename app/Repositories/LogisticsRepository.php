<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:42
 */

namespace App\Repositories;


use App\Base\BaseRepository;
use App\Models\Logistics\SupplierModel as Supplier;
use App\Models\LogisticsModel as Logistics;
use App\Models\WarehouseModel as Warehouse;

class LogisticsRepository extends BaseRepository
{
    protected $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required|active_url',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required|active_url',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
    ];

    public function __construct(Logistics $logistics)
    {
        $this->model = $logistics;
    }

    public function getSupplier()
    {
        return Supplier::all();
    }

    public function getWarehouse()
    {
        return Warehouse::all();
    }

}