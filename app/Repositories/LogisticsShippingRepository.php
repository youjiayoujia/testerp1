<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:42
 */

namespace App\Repositories;


use App\Base\BaseRepository;
use App\Models\LogisticsShippingModel as LogisticsShipping;
use App\Models\LogisticsModel as Logistics;
use App\Models\LogisticsTypeModel as LogisticsType;

class LogisticsShippingRepository extends BaseRepository
{
    protected $searchFields = ['short_code', 'logistics_id', 'type_id'];

    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse' => 'required',
            'logistics_id' => 'required',
            'type_id' => 'required',
            'url' => 'required|active_url',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse' => 'required',
            'logistics_id' => 'required',
            'type_id' => 'required',
            'url' => 'required|active_url',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
    ];

    public function __construct(LogisticsShipping $logisticsShipping)
    {
        $this->model = $logisticsShipping;
    }

    public function getLogistics()
    {
        return Logistics::all();
    }

    public function getLogisticsType()
    {
        return LogisticsType::all();
    }

}