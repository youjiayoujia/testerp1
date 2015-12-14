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

use Illuminate\Http\Request;
use App\Repositories\WarehousePositionRepository;


class warehousePositionController extends Controller
{
	protected $warehousePosition;

	function __construct(Request $request,warehousePositionRepository $warehousePosition)
	{
		$this->warehousePosition = $warehousePosition;
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
			'data' => $this->warehousePosition->paginate(),
		];

		return view('warehousePosition.index', $response);
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
			'warehousePosition' => $this->warehousePosition->detail($id),
		];

		return view('warehousePosition.show', $response);
	}

	/*
	*
	* @return view/create
	* @12:7pm
	*
	*/
	public function create()
	{
		$response = [
			'warehousePosition' => $this->warehousePosition->getwarehouse()
		];
		return view('warehousePosition.create', $response);
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
			'name' => 'required|max:128|unique:warehouse_positions,name',
			'warehouses_id' => 'required',
			'size' => 'required'
		];
		$this->validate($this->request,$rules);

		$data = [];
		$data['name'] = $this->request->input('name');
		$data['warehouses_id'] = $this->request->input('warehouses_id');
		$data['remark'] = $this->request->input('remark');
		$data['size'] = $this->request->input('size');
		$data['is_available'] = $this->request->input('is_available');

		$this->warehousePosition->store($data);
		return redirect(route('warehousePosition.index'));
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
			'warehousePositions' =>$this->warehousePosition->getwarehouse(),
			'warehousePosition' => $this->warehousePosition->detail($id),
		];

		return view('warehousePosition.edit',$response);
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
		$data['warehouses_id'] = $this->request->input('warehouses_id');
		$data['remark'] = $this->request->input('remark');
		$data['size'] = $this->request->input('size');
		$data['is_available'] = $this->request->input('is_available');
		$this->warehousePosition->update($id, $data);

		return redirect(route('warehousePosition.index'));
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
		$this->warehousePosition->destroy($id);
		return redirect(route('warehousePosition.index'));
	}
}