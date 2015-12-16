<?php

/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * User: MC
 * Date: 15/12/10
 * Time: 12:02pm
 */

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Repositories\WarehouseRepository;


class warehouseController extends Controller
{
	protected $warehouse;

	function __construct(Request $request,WarehouseRepository $warehouse)
	{
		$this->warehouse = $warehouse;
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
			'data' => $this->warehouse->paginate(),
		];

		return view('warehouse.index', $response);
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
			'warehouse' => $this->warehouse->get($id),
		];

		return view('warehouse.show', $response);
	}

	/*
	*
	* @return view/create
	* @12:7pm
	*
	*/
	public function create()
	{
		return view('warehouse.create');
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
			'name' => 'required|max:128|unique:warehouses,name',
			'type' => 'required',
			'volumn' => 'required|digits_between:1,10'
		];
		$this->validate($this->request,$rules);

		$data = [];
		$data['name'] = $this->request->input('name');
		$data['detail_address'] = $this->request->input('province')." ".$this->request->input('city');
		$data['type'] = $this->request->input('type');
		$data['volumn'] = $this->request->input('volumn');
		$data['is_available'] = $this->request->input('is_available');
		$data['is_default'] = $this->request->input('is_default');
		$this->warehouse->store($data);

		return redirect(route('warehouse.index'));
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
			'warehouse' => $this->warehouse->get($id),
		];

		return view('warehouse.edit',$response);
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
		$data['detail_address'] = $this->request->input('province')." ".$this->request->input('city');
		$data['type'] = $this->request->input('type');
		$data['volumn'] = $this->request->input('volumn');
		$data['is_available'] = $this->request->input('is_available');
		$data['is_default'] = $this->request->input('is_default');
		$this->warehouse->update($id, $data);

		return redirect(route('warehouse.index'));
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
		$this->warehouse->destroy($id);
		return redirect(route('warehouse.index'));
	}
}