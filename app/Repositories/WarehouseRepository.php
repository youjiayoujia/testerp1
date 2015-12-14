<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/3
 * Time: 下午3:37
 */

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\WarehouseModel as Warehouse;

class WarehouseRepository extends BaseRepository
{
    public $columns = ['id', 'name', 'country', 'created_at', 'updated_at'];

    protected $filters = ['name', 'country'];

    public $rules = [
        'warehouse_name' => 'required|unique:warehouses,name,',
        'country' => 'required',
    ];

    public function __construct(Warehouse $warehouse)
    {
        $this->model = $warehouse;
    }

    public function store($request)
    {
        $this->model->name = $request->input('warehouse_name');
        $this->model->country = $request->input('country');

        return $this->model->save();
    }

    public function update($id, $request)
    {
        $warehouse = $this->model->find($id);
        $warehouse->name = $request->input('warehouse_name');
        $warehouse->country = $request->input('country');

        return $warehouse->save();
    }
}