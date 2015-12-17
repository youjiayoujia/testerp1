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
        'create' => [
        	'name' => 'required|max:128|unique:warehouses,name',
			'type' => 'required',
			'volumn' => 'required|digits_between:1,10'
		],
        'update' => [
        	'name' => 'required|max:128|unique:warehouses,name,{id}',
			'type' => 'required',
			'volumn' => 'required|digits_between:1,10'
		]
    ];
    
	public function __construct(Warehouse $warehouse)
	{
		$this->model = $warehouse;
	}
	
    public function create($data)
    {
        $provider = $this->model->create($data);
        $provider->update(['detail_address' => $data['province'].' '.$data['city']]);
    }
}