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
	
}