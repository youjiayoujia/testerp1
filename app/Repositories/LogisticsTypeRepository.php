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
        'create' => ['type' => 'required|unique:logistics_type,type'],
        'update' => [],
    ];

    public function __construct(LogisticsType $logisticsType)
    {
        $this->model = $logisticsType;
    }

    public function store($data)
    {
        $logisticsType = LogisticsType::create($data);
        return $logisticsType;
    }

    public function update($id, $data)
    {
        $logisticsType = LogisticsType::where('id', '=', "{$id}")->update($data);
        return $logisticsType;
    }

    public function getLogistics()
    {
        return Logistics::all();
    }
}