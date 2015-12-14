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
			'columns' => $this->warehousePosition->columns,
			'data' => $this->warehousePosition->index($this->request),
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
		$this->validate($this->request,$this->warehousePosition->rules);
		$this->warehousePosition->store($this->request);
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
			'warehousePositions' => $this->warehousePosition->getWarehouse(),
			'warehousePosition' => $this->warehousePosition->edit($id),
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
		$this->warehousePosition->rules['name'] .= ','.$id;
		$this->validate($this->request, $this->warehousePosition->rules);
		$this->warehousePosition->update($id, $this->request);

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