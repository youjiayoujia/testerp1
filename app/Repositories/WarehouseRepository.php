<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\WarehouseModel as Warehouse;

/**
 * 范例: 仓库库
 *
 * @author MC<178069409@qq.com>
 */

class WarehouseRepository extends BaseRepository
{
	/**
	*  @func 用于显示
	*/
	public $columns = ['id', 'name', 'detail_address', 'type', 'volumn', 'is_available', 'is_default', 'created_at'];

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
		'name' => 'required|max:128|unique:warehouses,name',
		'type' => 'required',
		'volumn' => 'required|digits_between:1,10'
	];

	public function __construct(Warehouse $warehouse)
	{
		$this->model = $warehouse;
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
		$this->model->detail_address = $request->input('province')." ".$request->input('city');
		$this->model->type = $request->input('type');
		$this->model->volumn = $request->input('volumn');
		$this->model->is_available = $request->input('is_available');
		$this->model->is_default = $request->input('is_default');

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
		$res = Warehouse::find($id);

		$res->name = $request->input('name');
		$res->detail_address = $request->input('province')." ".$request->input('city');
		$res->type = $request->input('type');
		$res->volumn = $request->input('volumn');
		$res->is_available = $request->input('is_available');
		$res->is_default = $request->input('is_default');

		return $res->save();
	}

	/*
	*
		@func 返回Warehouse表的所有数据用于显示
		@ 13:50pm
	*
	*/
	public function getWarehouses()
	{
		return Warehouse::all();
	}
}