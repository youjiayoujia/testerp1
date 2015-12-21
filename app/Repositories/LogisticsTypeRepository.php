<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/17
 * Time: 下午3:03
 */

namespace App\Repositories;


use App\Base\BaseRepository;
use App\Models\LogisticsModel as Logistics;
use App\Models\LogisticsTypeModel as LogisticsType;

class LogisticsTypeRepository extends BaseRepository
{
    protected $searchFields = ['type'];

    public $rules = [
        'create' => [
            'type' => 'required',
            'logistics_id' => 'required',
        ],
        'update' => [
            'type' => 'required',
            'logistics_id' => 'required',
        ],
    ];

    public function __construct(LogisticsType $logisticsType)
    {
        $this->model = $logisticsType;
    }

    public function getLogistics()
    {
        return Logistics::all();
    }

}