<?php
/**
 * 出库控制器
 * 处理出库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/24
 * Time: 11:05am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\OutRepository;
use App\Models\ItemModel as Item;
use App\Repositories\WarehouseRepository as warehouse;
use App\Repositories\Warehouse\PositionRepository as position;

class OutController extends Controller
{
    protected $out;

    public function __construct(Request $request, OutRepository $out)
    {
        $this->out = $out;
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
            'data' => $this->out->auto()->paginate(),
            'outname' => config('out'),
        ];

        return view('stock.out.index', $response);
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
            'stockout' => $this->out->get($id),
        ];

        return view('stock.out.show', $response);
    }

    /**
     * 跳转创建页 
     *
     * @param none
     * @return view
     *
     */
    public function create(warehouse $warehouse, position $position)
    {
        $response = [
            'data' => config('out'),
            'item' => json_encode(Item::all()->toArray()),
            'warehouses' => $warehouse->all(),
            'position' => json_encode($position->all()->toArray()),

        ];

        return view('stock.out.create', $response);
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

        $this->validate($this->request, $this->out->rules('create'));
        $this->out->create($this->request->all());

        return redirect(route('out.index'));
    }

    /**
     * 跳转数据编辑页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id, warehouse $warehouse, position $position)
    {
        $response = [
            'data' => config('out'),
            'out' => $this->out->get($id),
            'item' => json_encode(Item::all()->toArray()),
            'warehouses' => $warehouse->all(),
            'position' => json_encode($position->all()->toArray()),

        ];

        return view('stock.out.edit', $response);
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
        $this->validate($this->request, $this->out->rules('update', $id));
        $this->out->update($id, $this->request->all());

        return redirect(route('out.index'));
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
        $this->out->destroy($id);
        return redirect(route('out.index'));
    }
}