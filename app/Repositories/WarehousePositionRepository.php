<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\WarehousePositionModel as Position;
use App\Models\WarehouseModel as Warehouse;

/**
 * 范例: 库位库
 *
 * @author MC<178069409@qq.com>
 */

class WarehousePositionRepository extends BaseRepository
{
		/*
	*
	* @func 用于查询
	*
	*/
	protected $searchFields = ['name'];

	/**
	*
	* 规则验证
	*
	*/
    public $rules = [
        'create' => [
        	'name' => 'required|max:128|unique:warehouse_positions,name',
			'warehouses_id' => 'required',
			'size' => 'required'
			],
        'update' => [
        	'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
			'warehouses_id' => 'required',
			'size' => 'required'
			]
    ];

	public function __construct(Position $position)
	{
		$this->model = $position;
	}

	public function getWarehouse()
	{
		return Warehouse::all();
	}
}