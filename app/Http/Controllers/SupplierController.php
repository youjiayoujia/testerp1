<?php

/**
 *  供货商控制器
 *  处理与供货商相关的操作
 *
 *  @author:MC<178069409@qq.com>
 *	Date:2015/12/18
 *	Time:11:18
 *
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SupplierRepository;

class SupplierController extends Controller
{
	protected $supplier;

	function __construct(Request $request, SupplierRepository $supplier)
	{
		$this->supplier = $supplier;
		$this->request = $request;
	} 

	/**
	 * 供货商列表展示
	 *
	 * @param none
	 * @return view 
	 *
	 */
	public function index()
	{
		$this->request->flash();
		$response = [
			'data' => $this->supplier->auto()->paginate(),
		];

		return view('product.supplier.index', $response);
	}

	/**
	 * 显示供货商详细信息
	 *
	 * @param $id integer 列表的id
	 * @return view
	 *
	 */
	public function show($id)
	{
		$response = [
			'supplier' => $this->supplier->get($id),
		];

		return view('product.supplier.show', $response);
	}

	/**
	 * 新建一个供货商记录
	 *
	 * @param none
	 * @return view
	 *
	 */
	public function create()
	{
		return view('product.supplier.create');
	}

	/**
	 * 供货商信息保存
	 *
	 * @param none
	 * @return view
	 *
	 */
	public function store()
	{
		$this->request->flash();
		$this->validate($this->request, $this->supplier->rules('create'));
		$this->supplier->create($this->request->all());

		return redirect(route('supplier.index'));
	}

	/**
	 * 跳转供货商信息编辑页面
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function edit($id)
	{
		$response = [
			'supplier' => $this->supplier->get($id),
		];

		return view('product.supplier.edit',$response);
	}

	/**
	 * 供货商信息更新
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->supplier->rules('update', $id));
		$this->supplier->update($id, $this->request->all());
		
		return redirect(route('supplier.index'));
	}

	/**
	 * 删除记录
	 *
	 * @param $id integer 记录id
	 * @return view	
	 *
	 */
	public function destroy($id)
	{
		$this->supplier->destroy($id);

		return redirect(route('supplier.index'));
	}
}