<?php

/**
 * 产品控制器
 * 处理产品相关的Request与Response
 *
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProviderRepository;


class ProviderController extends Controller
{
	protected $provider;

	function __construct(Request $request,ProviderRepository $provider)
	{
		$this->provider = $provider;
		$this->request = $request;
	}

	public function index()
	{
		$this->request->flash();

		$response = [
			'columns' => $this->provider->columns,
			'data' => $this->provider->index($this->request),
		];

		return view('provider.index', $response);
	}

	public function show($id)
	{
		$response = [
			'provider' => $this->provider->detail($id),
		];

		return view('provider.show', $response);
	}

	public function create()
	{
		return view('provider.create');
	}

	public function store()
	{
		$this->request->flash();
		$this->validate($this->request,$this->provider->rules);
		$this->provider->store($this->request);
		return redirect(route('provider.index'));
	}

	public function edit($id)
	{
		$response = [
			'providers' => $this->provider->getProviders(),
			'product' => $this->provider->edit($id),
		];

		return view('provider.edit',$response);
	}

	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->provider->rules);
		$this->provider->update($id, $this->request);

		return redirect(route('provider.index'));
	}

	public function destroy($id)
	{
		$this->provider->destroy($id);
		return redirect(route('provider.index'));
	}
}