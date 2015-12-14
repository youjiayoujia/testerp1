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
        'create' => ['name' => 'required|unique:warehouse_positions,name'],
        'update' => []
    ];

	public function __construct(Position $position)
	{
		$this->model = $position;
	}

	/*
	*
	* @ func 保存数据
	*
	* @ param $request http:request
	* @ 13:45pm
	* 
	*/
	public function store($data)
	{
		$position = Position::create($data);
		return $position;
	}

 	/*
 	*
		@func 更新数据
 		@param $id 数据id  | $request http请求
 		@ 13:45 pm
	*
 	*/
	public function update($id, $data)
	{
		$position = Position::where('id', '=', "{$id}")->update($data);

		return $position;
	}

	/*
	*
		@func 返回position表的所有数据用于显示
		@ 13:50pm
	*
	*/
	public function getWarehouse()
	{
		return Warehouse::all();
	}
}