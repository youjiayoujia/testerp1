<?php
/**
 * 库存调拨控制器
 * 处理库存调整相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/1/11
 * Time: 11:09
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\AllotmentRepository;
use App\Models\ItemModel;
use App\Repositories\WarehouseRepository;
use App\Repositories\Warehouse\PositionRepository;
use App\Repositories\Stock\AllotmentFormRepository;
use App\Repositories\Stock\OutRepository;
use App\Repositories\StockRepository;


class AllotmentController extends Controller
{
    protected $allotment;
    protected $warehouse;
    protected $allotmentform;
    protected $out;
    protected $stock;

    public function __construct(Request $request, 
                                AllotmentRepository $allotment,
                                WarehouseRepository $warehouse,
                                PositionRepository $position,
                                AllotmentFormRepository $allotmentform,
                                OutRepository $out,
                                StockRepository $stock)
    {
        $this->allotment = $allotment;
        $this->request = $request;
        $this->warehouse = $warehouse;
        $this->position = $position;
        $this->allotmentform = $allotmentform;
        $this->out = $out;
        $this->stock = $stock;
        $this->mainIndex = route('stockAllotment.index');
        $this->mainTitle = '库存调拨';
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
            'warehouses' => $this->warehouse->all(),
            'data' => $this->allotment->auto()->paginate(),
        ];

        return view('stock.allotment.index', $response);
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
            'allotment' => $this->allotment->get($id),
            'allotmentforms' => $this->allotment->get($id)->allotmentform,
        ];
        
        return view('stock.allotment.show', $response);
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => $this->warehouse->all(),
        ];

        return view('stock.allotment.create', $response);
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
        $len = count(array_keys($this->request->input('arr')['sku']));
        $buf = $this->request->all();
        $obj = $this->allotment->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = $this->request->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_allotments_id'] = $obj->id;
            $this->allotmentform->create($buf);
        }

        return redirect(route('stockAllotment.index'));
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
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $this->allotment->get($id),
            'warehouses' => $this->warehouse->all(),
            'positions' => $this->position->get_position(['warehouses_id'=>$this->allotment->get($id)->out_warehouses_id]),
            'allotmentforms' => $this->allotment->get($id)->allotmentform, 
        ];

        return view('stock.allotment.edit', $response);
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
        $obj = $this->allotment->get($id)->allotmentform;
        $obj_len = count($obj);
        unset($buf['_token']);
        unset($buf['_method']);
        unset($buf['arr']);
        $this->allotment->update($id, $buf);
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            $arr = $this->request->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_allotments_id'] = $id;
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }

        return redirect(route('stockAllotment.index'));
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
        $this->allotment->destroy($id);

        return redirect(route('stockAllotment.index'));
    }

    /**
     * 处理ajax请求参数,审核
     *
     * @param none
     * @return json|time
     *
     */
    public function allotmentcheck()
    {
        $id = $_GET['id'];
        $time = date('Y-m-d',time());       
        $obj = $this->allotment->get($id);
        $obj->update(['check_status'=>'Y', 'check_time'=>$time]); 
        echo json_encode($time);

        $obj->relation_id = $obj->allotment_id;
        $arr = $obj->toArray();
        $buf = $obj->allotmentform->toArray();
        for($i=0;$i<count($buf);$i++) {
            $tmp = [];
            $tmp = array_merge($arr,$buf[$i]);
            $tmp['warehouses_id'] = $tmp['out_warehouses_id'];
            $tmp['type'] = 'ALLOTMENT';
            $this->stock->out($tmp);
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
            'allotment_time' => 'date|required',
            'out_warehouses_id' => 'required|numeric',
            'in_warehouses_id' => 'required|numeric',
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
            if($key == 'total_amount')
                foreach($val as $k => $v)
                {
                    $arr['arr.total_amount.'.$k] = 'required';
                }
        }

        return $arr;
    }
}