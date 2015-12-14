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
    public $columns = ['id', 'name', 'country', 'created_at', 'updated_at'];
    protected $filters = ['name', 'country'];
    public $rules = [
        'logistics_name' => 'required|unique:logistics,name,',
        'country' => 'required',
    ];

    public function __construct(Logistics $logistics)
    {
        $this->model = $logistics;
    }

    public function store($request)
    {
        $this->model->name = $request->input('logistics_name');
        $this->model->country = $request->input('country');
        return $this->model->save();
    }

    public function update($id, $request)
    {
        $logistics = $this->model->find($id);
        $logistics->name = $request->input('logistics_name');
        $logistics->country = $request->input('country');
        return $logistics->save();
    }
}