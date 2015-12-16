<?php

/**
 * 供货商控制器
 * 处理供货商相关的Request与Response
 *
 * User: MC
 * Date: 15/12/4
 * Time: 12:02pm
 */

namespace App\Http\Controllers;

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
            'data' => $this->provider->paginate(),
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

		$rules = [
			'name' => 'required|max:128|unique:providers,name',
			'address' => 'required|max:256',
			'url' => 'required|max:256|active_url',
			'telephone' => 'required|max:256|digits_between:8,11',
		];

		$this->validate($this->request,$rules);
 

		$data = [];
		$data['name'] = $this->request->input('name');
		$data['detail_address'] = $this->request->input('province')." ".$this->request->input('city');
		$data['address'] = $this->request->input('address');
		$data['type'] = $this->request->input('online') == '0' ? 'offline' : 'online';
		$data['url'] = $this->request->input('url');
		$data['telephone'] = $this->request->input('telephone');
		$data['purchase_id'] = $this->request->input('purchaseid');
		$data['level'] = $this->request->input('level');
		$data['created_by'] = $this->request->input('created_by');
		
        if ($this->request->has('setValues')) {
            foreach ($this->request->input('setValues') as $setValue) {
                $data['sets'][1]['values'][]['name'] = $setValue;
            }
        }
		$this->provider->store($data);
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

		$data = [];
		$data['name'] = $this->request->input('name');
		$data['detail_address'] = $this->request->input('province').' '.$this->request->input('city');
		$data['address'] = $this->request->input('address');
		$data['type'] = $this->request->input('online') == '0' ? 'offline' : 'online';
		$data['url'] = $this->request->input('url');
		$data['telephone'] = $this->request->input('telephone');
		$data['purchase_id'] = $this->request->input('purchaseid');
		$data['level'] = $this->request->input('level');
		$data['created_by'] = $this->request->input('created_by');
		$this->provider->update($id, $data);

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