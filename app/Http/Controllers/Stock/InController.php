<?php
/**
 * 入库控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\InRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\Warehouse\PositionRepository;

class InController extends Controller
{
    protected $in;

    public function __construct(Request $request, InRepository $in)
    {
        $this->in = $in;
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
            'data' => $this->in->auto()->paginate(),
        ];

        return view('stock.in.index', $response);
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
            'stockin' => $this->in->get($id),
        ];

        return view('stock.in.show', $response);
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
            'data' => config('in'),
            'warehouses' => $warehouse->all(),
        ];

        return view('stock.in.create', $response);
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
        $this->validate($this->request, $this->in->rules('create'));
        $this->in->create($this->request->all());

        return redirect(route('stockIn.index'));
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
            'data' => config('in'),
            'in' => $this->in->get($id),
            'warehouses' => $warehouse->all(),
        ];

        return view('stock.in.edit', $response);
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
        $this->validate($this->request, $this->in->rules('update'));
        $this->in->update($id, $this->request->all());

        return redirect(route('stockIn.index'));
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
        $this->in->destroy($id);

        return redirect(route('stockIn.index'));
    }

    /**
     * 获取itemid，返回
     *
     * @param none 
     * @return json
     *
     */
    public function getItemId()
    { 
        $sku_val = $_GET['sku_val']; 
        $id = $this->in->getitemid($sku_val);

        echo json_encode($id);
    }

    /**
     * 获取库位信息,return 
     *
     * @param none
     * @return json
     *
     */
    public function getPosition()
    {
        $warehouses_id = $_GET['val'];
        $arr = $this->in->getPosition($warehouses_id);

        echo json_encode($arr);
    }
}