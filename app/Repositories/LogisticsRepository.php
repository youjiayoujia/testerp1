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
        'create' => ['name' => 'required|unique:logistics,name'],
        'update' => []
    ];

    public function __construct(Logistics $logistics)
    {
        $this->model = $logistics;
    }

    public function store($data)
    {
        $logistics = Logistics::create($data);
        return $logistics;
    }

    public function update($id, $data)
    {
        $logistics = Logistics::where('id', '=', "{$id}")->update($data);
        return $logistics;
    }
}