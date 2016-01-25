<?php
/**
 * 库存调整控制器
 * 处理库存调整相关的Request与Response
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
use App\Repositories\Stock\AdjustFormRepository;

class AdjustmentController extends Controller
{
    protected $adjustment;
    protected $out;
    protected $in;
    protected $stock;
    protected $adjust;

    public function __construct(Request $request, 
                                AdjustmentRepository $adjustment, 
                                InRepository $in, 
                                OutRepository $out, 
                                StockRepository $stock,
                                AdjustFormRepository $adjust)
    {
        $this->adjustment = $adjustment;
        $this->request = $request;
        $this->out = $out;
        $this->in = $in;
        $this->stock = $stock;
        $this->adjust = $adjust;
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
            'data' => $this->adjust->auto()->paginate(),
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
            'adjustments' => $this->adjust->get($id)->adjustment,
            'adjust' => $this->adjust->get($id),
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
        $obj = $this->adjust->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = $this->request->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['adjust_forms_id'] = $obj->id;
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
    public function edit($id, WarehouseRepository $warehouse, PositionRepository $position)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'adjust' => $this->adjust->get($id),
            'adjustments' => $this->adjust->get($id)->adjustment,
            'warehouses' => $warehouse->all(),
            'positions' =>$position->get_position(['warehouses_id' => $this->adjust->get($id)->warehouses_id])->toArray(),
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
        $this->validate($this->request, $this->rules($this->request));
        $buf = [];
        $len = count(array_keys($this->request->input('arr')['sku']));
        $buf = $this->request->all();
        $obj = $this->adjust->get($id)->adjustment;
        $obj_len = count($obj);

        $this->adjust->update($id, $buf);
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            $arr = $this->request->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['adjust_forms_id'] = $id;

            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }

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
        $this->adjust->destroy($id);
        return redirect(route('stockAdjustment.index'));
    }

    /**
     * 处理ajax请求参数,审核
     *
     * @param none
     * @return json|time
     *
     */
    public function check()
    {
        $id = $_GET['id'];
        $time = date('Y-m-d',time());       
        $obj = $this->adjust->get($id);
        $obj->update(['status'=>'Y', 'check_time'=>$time]); 
        echo json_encode($time);

        $obj->relation_id = $obj->adjust_form_id;
        $arr = $obj->toArray();
        $buf = $obj->adjustment->toArray();
        for($i=0;$i<count($buf);$i++) {
            $tmp = [];
            $tmp = array_merge($arr,$buf[$i]);
            if($tmp['type'] == '入库') {
                $tmp['type'] = 'ADJUSTMENT';
                $this->stock->in($tmp);
            } else {
                $tmp['type'] = 'ADJUSTMENT';
                $this->stock->out($tmp);
            }
        }
    }

    /**
     * 返回验证规则 
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