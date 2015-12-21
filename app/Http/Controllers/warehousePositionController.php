<?php
/**
 * 库位控制器
 * 处理库位相关的Request与Response
 *
 * @author: MC
 * Date: 15/12/18
 * Time: 16:15pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\WarehousePositionRepository;
use App\Models\WarehouseModel as Warehouse;

class warehousePositionController extends Controller
{
	protected $warehousePosition;

	function __construct(Request $request,warehousePositionRepository $warehousePosition)
	{
		$this->warehousePosition = $warehousePosition;
		$this->request = $request;
	}

	/**
	 * 数据列表显示页 
	 *
	 * @param none
	 * @return view
	 *
	 */
	public function index()
	{
		$this->request->flash();

		$response = [
			'data' => $this->warehousePosition->auto()->paginate(),
		];
	
		return view('warehousePosition.index', $response);
	}

	/**
	 * 库位详情页 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function show($id)
	{
		$response = [
			'warehousePosition' => $this->warehousePosition->get($id),
		];

		return view('warehousePosition.show', $response);
	}

	/**
	 * 跳转创建页 
	 *
	 * @param none
	 * @return none
	 *
	 */
	public function create(Warehouse $warehouse)
	{
		$response = [
			'warehouses' => $warehouse->all(),
		];
		return view('warehousePosition.create', $response);
	}

	/**
	 * 数据保存 
	 *
	 * @param none
	 * @return view
	 *
	 */
	public function store()
	{
		$this->request->flash();
		$this->validate($this->request,$this->warehousePosition->rules('create'));
		$this->warehousePosition->create($this->request->all());
		return redirect(route('warehousePosition.index'));
	}

	/**
	 * 跳转数据编辑页 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function edit($id, Warehouse $warehouse)
	{
		$response = [
			'warehouses' => $warehouse->all(),
			'warehousePosition' => $this->warehousePosition->get($id),
		];

		return view('warehousePosition.edit',$response);
	}

	/**
	 * 数据更新 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->warehousePosition->rules('update', $id));
		$this->warehousePosition->update($id, $this->request->all());

		return redirect(route('warehousePosition.index'));
	}

	/**
	 * 记录删除 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function destroy($id)
	{
		$this->warehousePosition->destroy($id);
		return redirect(route('warehousePosition.index'));
	}
}