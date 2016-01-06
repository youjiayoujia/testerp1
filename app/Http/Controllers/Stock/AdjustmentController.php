<?php
/**
 * 库存调整控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/24
 * Time: 14:22
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\AdjustmentRepository;
use App\Models\ItemModel;
use App\Repositories\WarehouseRepository;
use App\Repositories\Warehouse\PositionRepository;
use App\Repositories\Stock\InRepository;
use App\Repositories\Stock\OutRepository;
use App\Repositories\StockRepository;

class AdjustmentController extends Controller
{
    protected $adjustment;
    protected $out;
    protected $in;
    protected $stock;

    public function __construct(Request $request, AdjustmentRepository $adjustment, InRepository $in, OutRepository $out, StockRepository $stock)
    {
        $this->adjustment = $adjustment;
        $this->request = $request;
        $this->out = $out;
        $this->in = $in;
        $this->stock = $stock;
        $this->mainIndex = route('stockAdjustment.index');
        $this->mainTitle = '库存调整';
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
            'data' => $this->adjustment->auto()->paginate(),
        ];

        return view('stock.adjustment.index', $response);
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
            'adjustment' => $this->adjustment->get($id),
        ];

        return view('stock.adjustment.show', $response);
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

        return view('stock.adjustment.create', $response);
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
        $this->validate($this->request, $this->rules($this->request));
        $buf = [];
        $len = count(array_keys($this->request->input('arr')['sku']));
        $buf = $this->request->all();
        unset($buf['_token']);
        unset($buf['arr']);
        for($i=0; $i<$len; $i++)
        {   
            $arr = $this->request->input('arr');
            var_dump($arr);
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $this->adjustment->create($buf);
        }
        return redirect(route('stockAdjustment.index'));
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
            'adjustment' => $this->adjustment->get($id),
            'warehouses' => $warehouse->all(),
        ];

        return view('stock.adjustment.edit', $response);
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
        $this->validate($this->request, $this->adjustment->rules('update'));
        $this->adjustment->update($id, $this->request->all());

        return redirect(route('stockAdjustment.index'));
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
        $this->adjustment->destroy($id);
        return redirect(route('stockAdjustment.index'));
    }

    /**
     * 处理ajax请求参数 
     *
     * @param none
     * @return json|time
     *
     */
    public function check()
    {
        $id = $_GET['id'];
        $time = date('Y-m-d',time());       
        $obj = $this->adjustment->get($id);
        $obj->update(['status'=>'Y', 'check_time'=>$time]); 
        echo json_encode($time);

        $obj->relation_id = $obj->adjust_form_id;
        $arr = $obj->toArray();
        if($obj->type == '入库') {
            $arr['type'] = 'ADJUSTMENT';
            $this->stock->in($arr);
        } else {
            $arr['type'] = 'ADJUSTMENT';
            $this->stock->out($arr);
        }
    }

    /**
     * 返回create的验证规则 
     *
     * @param $request
     * @return $arr
     *
     */
    public function rules($request)
    {
        $arr = [
            'adjust_time' => 'date|required',
        ];
        $buf = $request->all();
        $buf = $buf['arr'];
        foreach($buf as $key => $val) 
        {
            if($key == 'sku')
                foreach($val as $k => $v)
                {
                    $arr['arr.sku.'.$k] ='required';
                }
            if($key == 'amount')
                foreach($val as $k => $v)
                {
                    $arr['arr.amount.'.$k] ='required|numeric';
                }
            if($key == 'warehouse_positions_id')
                foreach($val as $k => $v)
                {
                    $arr['arr.warehouse_positions_id.'.$k] = 'required|numeric';
                }
        }

        return $arr;
    }
}