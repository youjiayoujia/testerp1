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
use App\Repositories\Warehouse\PositionRepository;

class StockController extends Controller
{
    protected $stock;
    protected $position;

    public function __construct(Request $request, StockRepository $stock, PositionRepository $position)
    {
        $this->stock = $stock;
        $this->position = $position;
        $this->request = $request;
        $this->mainIndex = route('stock.index');
        $this->mainTitle = '库存';
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
            'metas' => $this->metas(__FUNCTION__),
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
            'metas' => $this->metas(__FUNCTION__),
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
            'metas' => $this->metas(__FUNCTION__),
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
            'metas' => $this->metas(__FUNCTION__),
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

        if($unit) {
            echo json_encode($unit);
        } else {
            echo json_encode('1');
        }
    }

    /**
     * 获取sku
     * 
     * @param none
     * @return 'none' or json
     *
     */
    public function getSku()
    {
        $val_position = $_GET['val_position'];
        $obj = $this->stock->getObj(['warehouse_positions_id'=>$val_position])->first();

        if($obj)
            echo json_encode([$obj->sku, $obj->available_amount, $obj->item_id]);
        else
            echo json_encode('none');
    }

    /**
     * 获取product 可用数量 
     *
     * @param none
     * @return json|可用数量
     *
     */
    public function getAvailableAmount()
    {
        $position = $_GET['position'];
        $obj = $this->stock->getObj(['warehouse_positions_id'=>$position])->first();
        $cost = $this->stock->getunitcost($obj->sku);
        if($obj)
            echo json_encode([$obj->available_amount,$cost]);
    }

    /**
     * 根据仓库获取position,根据第一个Position获取item_id,sku,available_amount 
     *
     * @param none
     * @return json
     *
     */
    public function getpsi()
    {
        $warehouse = $_GET['warehouse'];

        $arr[] = $this->position->get_position(['warehouses_id'=>$warehouse], ['id', 'name'])->toArray();
        

        $obj = $this->stock->getObj(['warehouse_positions_id'=>$arr[0][0]['id']], ['item_id', 'sku', 'available_amount'])->first();
        if($obj) {
            $arr[1][] = $obj ->toArray();
        }

        if($arr)
            echo json_encode($arr);
        else
            echo 'none';
    }

    /**
     * 根据仓库库位定sku 
     *
     * @param none
     * @return json
     *
     */
    public function stockposition()
    {
        $warehouse = $_GET['warehouse'];
        $position = $_GET['position'];

        $buf = $this->stock->getObj(['warehouses_id'=>$warehouse, 'warehouse_positions_id'=>$position], ['sku'])->first();

        if($buf->toArray())
            echo json_encode($buf);
        else
            echo json_encode('none');
    }
}