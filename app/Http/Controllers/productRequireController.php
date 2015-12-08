<?php

/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * User: MC<178069409@qq.com>
 * Date: 15/12/4
 * Time: 13:49pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\productRequireRepository;

class productRequireController extends Controller
{
	protected $productRequire;

	function __construct(Request $request, productRequireRepository $productRequire)
	{
		$this->request = $request;
		$this->productRequire = $productRequire;
	}

	/*
	*
		@ func 显示主界面 
		@ view/index 传参，columns和data

		@1:50pm
	*
	*/
	public function index()
	{
		$this->request->flash();
		$response = [
			'columns' => $this->productRequire->column,
			'data' => $this->productRequire->index($this->request),
		];

		return view('productRequire.index', $response);
	}

	/*
	*
		@func   显示需求详细信息
		@ view/show  传参response 

		@ 1:50am
	*
	*/

	public function show($id)
	{
		$response = [
			'productRequire' => $this->productRequire->detail($id),
		];

		return view('productRequire.show', $response);
	}

	/*
	*
		@func 创建记录
		@retrun view/create

		@1:53pm
	*
	*/
	public function create()
	{
		return view('productRequire.create');
	}


	/*
	*
		@func 保存记录
		@转移图片存储位置，返回路径

		@ 1:50pm
	*
	*/
	public function store()
	{
		$this->request->flash();

		$this->validate($this->request, $this->productRequire->rules);

		$this->productRequire->store($this->request);
		return redirect(route('productRequire.index'));
	}

	/*
	*
		@func 更新数据
		@param $id 数据的id

		@13:57pm
	*
	*/

	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->productRequire->rules);
		$this->productRequire->update($id, $this->request);
		return redirect(route('productRequire.index'));
	}

	/*
	*
		@func 编辑需求
		@ param id 数据记录的id

		@return view/edit
		@1:50pm
	*
	*/
	public function edit($id)
	{
		$response = [
			'productRequire' => $this->productRequire->edit($id),
		];

		return view('productRequire.edit', $response);
	}

	/*
	*
		@func 删除一条记录
		@param  $id  记录的id

		@return view/destroy
	*
	*/

	public function destroy($id)
	{
		$this->fashion->destroy($id);

		return redirect(route('productRequire.index'));
	}

}