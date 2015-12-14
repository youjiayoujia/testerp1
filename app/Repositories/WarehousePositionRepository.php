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
	/**
	*  @func 用于显示
	*/
	public $columns = ['id', 'name', 'warehouse_id', 'remark', 'size', 'is_available', 'created_at'];

	/*
	*
	* @func 用于查询
	*
	*/
	protected $filters = ['name'];

	/*
	*
	* @ 用于验证
	*
	*/
	public $rules = [
		'name' => 'required|max:128|unique:warehouse_positions,name',
		'warehouses_id' => 'required',
		'size' => 'required'
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
	public function store($request)
	{
		$this->model->name = $request->input('name');
		$this->model->warehouses_id = $request->input('warehouses_id');
		$this->model->remark = $request->input('remark');
		$this->model->size = $request->input('size');
		$this->model->is_available = $request->input('is_available');

		return $this->model->save();
	}

 	/*
 	*
		@func 更新数据
 		@param $id 数据id  | $request http请求
 		@ 13:45 pm
	*
 	*/
	public function update($id, $request)
	{
		$res = position::find($id);

		$res->name = $request->input('name');
		$res->warehouses_id = $request->input('warehouses_id');
		$res->remark = $request->input('remark');
		$res->size = $request->input('size');
		$res->is_available = $request->input('is_available');

		return $res->save();
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