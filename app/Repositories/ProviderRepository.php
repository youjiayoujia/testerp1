<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\ProviderModel as Provider;

/**
 * 范例: 供应商库
 *
 * @author MC<nyewon@gmail.com>
 */

class ProviderRepository extends BaseRepository
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
        'create' => ['name' => 'required|unique:providers,name'],
        'update' => []
    ];


	public function __construct(Provider $provider)
	{
		$this->model = $provider;
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
		$provider = $this->model->create($data);
		return $provider;

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
		$provider = Provider::where('id', '=', "{$id}")->update($data);

		return $provider;
	}


}