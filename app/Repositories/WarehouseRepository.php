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
        'create' => ['name' => 'required|unique:warehouses,name'],
        'update' => []
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
	public function store($data)
	{
		$warehouse = Warehouse::create($data);

		return $warehouse;
	}

 	/*
 	*
		@func 更新数据
 		@param $id 数据id  | $data http请求
 		@ 13:45 pm
	*
 	*/
	public function update($id, $data)
	{
		$warehouse = Warehouse::where('id', '=', "{$id}")->update($data);

		return $warehouse;
	}

	
}