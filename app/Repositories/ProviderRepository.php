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
	public $columns = ['id', 'name', 'address', 'isonline_provider', 'url', 'telephone', 'purchase_id', 'level', 'created_at'];

	protected $filters = ['name'];

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

	public function store($request)
	{
		$this->model->name = $request->input('name');
		$this->model->address = $request->input('address');
		$this->model->isonline_provider = $request->input('online');
		$this->model->url = $request->input('url');
		$this->model->telephone = $request->input('telephone');
		$this->model->purchase_id = $request->input('purchaseid');
		$this->model->level = $request->input('level');

		return $this->model->save();
	}

	public function update($id, $request)
	{
		$res = Provider::find($id);

		$res->name = $request->input('name');
		$res->address = $request->input('address');
		$res->isonline_provider = $request->input('online');
		$res->url = $request->input('url');
		$res->telephone = $request->input('telephone');
		$res->purchase_id = $request->input('purchaseid');
		$res->level = $request->input('level');

		return $res->save();
	}

	public function getProviders()
	{
		return Provider::all();
	}
}