<?php
namespace App\Http\Controllers;

use App\Commands\ss;
use Queue;
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

	public function test()
	{
		Queue::later(10, new ss());
		echo "ok";
	}
	/*
	*
	* @ 数据一次压session
	* @ 向index视图传参 columns|data
	*
	* @return view
	* @12:05pm
	*/
	public function index()
	{
		$this->request->flash();

		$response = [
			'data' => $this->provider->auto()->paginate(),
		];

		return view('provider.index', $response);
	}

	/*
	*
	* @$response 向show 模板传参
	*
	* @retrun view/show
	* @ 12:7pm
	* 
	*/
	public function show($id)
	{
		$response = [
			'provider' => $this->provider->get($id),
		];

		return view('provider.show', $response);
	}

	/*
	*
	* @return view/create
	* @12:7pm
	*
	*/
	public function create()
	{
		return view('provider.create');
	}

	/*
	*
	*
	* @ 数据一次压session
	* @ 验证规则
	* @ 数据存储
	* @ 12:15pm
	*
	*/
	public function store()
	{
		$this->request->flash();
		$this->validate($this->request,$this->provider->rules('create'));
		$this->provider->create($this->request->all());

		return redirect(route('provider.index'));
	}

	/*
	*
	* @ param $id 数据的id
	*
	* @ return view/edit
	* @ 12:15pm
	*/
	public function edit($id)
	{
		$response = [
			'product' => $this->provider->get($id),
		];

		return view('provider.edit',$response);
	}

	/*
	*
	*
	* @供货商更新
	* @ param id 记录的数据id
	* 
	* @return view
	* @ 12:17pm
	*/
	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->provider->rules('update', $id));
		$this->provider->update($id, $this->request->all());
		
		return redirect(route('provider.index'));
	}

	/*
	*
	* @ 供货商删除
	* @ param $id 记录id
	* @ return view
	* 
	* @12:19pm
	*
	*/
	public function destroy($id)
	{
		$this->provider->destroy($id);
		return redirect(route('provider.index'));
	}
}