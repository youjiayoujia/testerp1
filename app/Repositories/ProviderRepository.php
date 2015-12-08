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
	/**
	*  @func 用于显示
	*/
	public $columns = ['id', 'name', 'detail_address', 'address', 'type', 'url', 'telephone', 'purchase_id', 'level', 'created_by', 'created_at'];

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
		'name' => 'required|max:128|',
		'address' => 'required|max:256',
		'url' => 'required|max:256|active_url',
		'telephone' => 'required|max:256|digits_between:8,11',
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
	public function store($request)
	{
		$this->model->name = $request->input('name');
		$this->model->detail_address = $request->input('province').$request->input('city');
		$this->model->address = $request->input('address');
		$this->model->type = $request->input('online') == '0' ? 'offline' : 'online';
		$this->model->url = $request->input('url');
		$this->model->telephone = $request->input('telephone');
		$this->model->purchase_id = $request->input('purchaseid');
		$this->model->level = $request->input('level');
		$this->model->created_by = $request->input('created_by');

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
		$res = Provider::find($id);

		$res->name = $request->input('name');
		$res->detail_address = $request->input('province').$request->input('city');
		$res->address = $request->input('address');
		$res->type = $request->input('online') == '0' ? 'offline' : 'online';
		$res->url = $request->input('url');
		$res->telephone = $request->input('telephone');
		$res->purchase_id = $request->input('purchaseid');
		$res->level = $request->input('level');
		$res->created_by = $request->input('created_by');

		return $res->save();
	}

	/*
	*
		@func 返回provider表的所有数据用于显示
		@ 13:50pm
	*
	*/
	public function getProviders()
	{
		return Provider::all();
	}
}