<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Warehouse\WarehouseRepository;

class warehouseController extends Controller
{
	protected $warehouse;

	function __construct(Request $request, WarehouseRepository $warehouse)
	{
		$this->warehouse = $warehouse;
		$this->request = $request;
	}

	/**
	* 列表显示页
	*
	* @param none
	* @return view
	*
	*/
	public function index()
	{
		$this->request->flash();

		$response = [
			'data' => $this->warehouse->auto()->paginate(),
		];

		return view('warehouse.index', $response);
	}

	/**
	 * 信息详情页 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function show($id)
	{
		$response = [
			'warehouse' => $this->warehouse->get($id),
		];

		return view('warehouse.show', $response);
	}

	/**
	 * 跳转创建页 
	 *
	 * @param none
	 * @return view
	 *
	 */
	public function create()
	{
		return view('warehouse.create');
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

		$this->validate($this->request, $this->warehouse->rules('create'));
		$this->warehouse->create($this->request->all());

		return redirect(route('warehouse.index'));
	}

	/**
	 * 跳转数据编辑页 
	 *
	 * @param $id integer 记录id
	 * @return view
	 *
	 */
	public function edit($id)
	{
		$response = [
			'warehouse' => $this->warehouse->get($id),
		];

		return view('warehouse.edit', $response);
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
		$this->validate($this->request, $this->warehouse->rules('update', $id));
		$this->warehouse->update($id, $this->request->all());

		return redirect(route('warehouse.index'));
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
		$this->warehouse->destroy($id);
		return redirect(route('warehouse.index'));
	}
}