<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StockRepository;
use App\Repositories\WarehouseRepository;

class StockController extends Controller
{
    protected $stock;

    public function __construct(Request $request, StockRepository $stock)
    {
        $this->stock = $stock;
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
            'data' => $this->stock->auto()->paginate(),
        ];

        return view('stock.index', $response);
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
            'stock' => $this->stock->get($id),
        ];

        return view('stock.show', $response);
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return view
     *
     */
    public function create(WarehouseRepository $warehouse)
    {
        $response = [
            'warehouses' => $warehouse->all(),
        ];

        return view('stock.create', $response);
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
        $this->validate($this->request, $this->stock->rules('create'));
        $this->stock->create($this->request->all());

        return redirect(route('stock.index'));
    }

    /**
     * 跳转数据编辑页
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id, WarehouseRepository $warehouse)
    {
        $response = [
            'stock' => $this->stock->get($id),
            'warehouses' => $warehouse->all(),
        ];

        return view('stock.edit', $response);
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
        $this->validate($this->request, $this->stock->rules('update', $id));
        $this->stock->update($id, $this->request->all());

        return redirect(route('stock.index'));
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
        $this->stock->destroy($id);
        return redirect(route('stock.index'));
    }

    /**
     *  获取某商品的平均单价
     *
     * @param none
     * @return json
     *
     */
    public function getUnitCost()
    {  
        $sku = $_GET['sku'];
        $unit = $this->stock->getunitcost($sku);

        echo json_encode($unit);
    }
}