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

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\AllotmentRepository;
use App\Models\ItemModel;
use App\Repositories\WarehouseRepository;
use App\Repositories\Warehouse\PositionRepository;
use App\Repositories\Stock\AllotmentFormRepository;
use App\Repositories\Stock\OutRepository;
use App\Repositories\StockRepository;
use App\Repositories\Stock\AllotmentLogisticsRepository;

class AllotmentController extends Controller
{
    protected $allotment;
    protected $warehouse;
    protected $allotmentform;
    protected $out;
    protected $stock;
    protected $logistics;

    public function __construct(Request $request, 
                                AllotmentRepository $allotment,
                                WarehouseRepository $warehouse,
                                PositionRepository $position,
                                AllotmentFormRepository $allotmentform,
                                OutRepository $out,
                                StockRepository $stock,
                                AllotmentLogisticsRepository $logistics)
    {
        $this->allotment = $allotment;
        $this->request = $request;
        $this->warehouse = $warehouse;
        $this->position = $position;
        $this->allotmentform = $allotmentform;
        $this->out = $out;
        $this->stock = $stock;
        $this->logistics = $logistics;
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
        $obj->update(['check_status'=>'Y', 'check_time'=>$time, 'allotment_status'=>'out']); 
        echo json_encode($time);

        $obj->relation_id = $obj->allotment_id;
        $arr = $obj->toArray();
        $buf = $obj->allotmentform->toArray();
        for($i=0;$i<count($buf);$i++) {
            $tmp = [];
            $tmp = array_merge($arr, $buf[$i]);
            $tmp['warehouses_id'] = $tmp['out_warehouses_id'];
            $tmp['type'] = 'ALLOTMENT';
            $this->stock->out($tmp);
        }
    }

    /**
     * 返回验证规则 
     *
     * @param $request request请求
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

    /**
     * ajax请求函数
     *  
     * @param none
     * @return json
     *
     */
    public function allotmentpick()
    {
        $id = $_GET['id'];
        $this->allotment->get($id)->update(['allotment_status'=>'pick']);
        echo json_encode('11');
    }

    /**
     * 跳转对单页面 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function checkform($id)
    {
        $obj = $this->allotment->get($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $obj,
            'allotmentforms' => $obj->allotmentform,
            'warehouses' => $this->warehouse->all(),
            'positions' => $this->position->get_position(['warehouses_id'=>$obj->in_warehouses_id]),
        ];

        return view('stock.allotment.checkform', $response);
    }

    /**
     * 对单数据更新 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function checkformupdate($id)
    {
        $this->request->flash();
        $arr = $this->request->all();
        $obj = $this->allotment->get($id)->allotmentform;
        
        DB::beginTransaction();
        try {
            $buf[] = $arr['arr']['receive_amount'];
            $buf[] = $arr['arr']['warehouse_positions_id'];
            $buf[] = $arr['arr']['old_receive_amount'];
            for($i=0; $i<count($buf[0]); $i++)
            {   
                $obj[$i]->update(['receive_amount'=>($buf[0][$i]+$buf[2][$i]), 'in_warehouse_positions_id'=>$buf[1][$i]]);
            }
            $flag = 1;
            $buf[] = $arr['arr']['amount'];
            $buf[] = $arr['arr']['receive_amount'];
            for($i=0;$i<count($buf[3]);$i++)
            {
                if($buf[3][$i] != ($buf[4][$i] + $buf[2][$i]))
                    $flag = 0;
            }
            if($flag == 1)
            {
                $arr['allotment_status'] = 'over';
            } else {
                $arr['allotment_status'] = 'check';
            }

            $arr['checkform_time'] = date('Y-m-d',time());
            $this->allotment->get($id)->update(['allotment_status'=>$arr['allotment_status'], 'checkform_time'=>$arr['checkform_time'], 'remark'=>$arr['remark']]);

            $len = count($arr['arr']['item_id']);
            for($i=0; $i<$len; $i++)
            {
                $buf = [];
                foreach($arr['arr'] as $key => $value)
                {
                    $buf[$key] = $value[$i];
                }
                 $buf = array_merge($buf,$arr);
                 $buf['type'] = "ALLOTMENT";
                 $buf['warehouses_id'] = $this->allotment->get($id)->in_warehouses_id;
                 $buf['relation_id'] = $buf['allotment_id'];
                 $buf['total_amount'] = round($buf['total_amount']/$buf['amount']*$buf['receive_amount'],3);
                 $buf['amount'] = $buf['receive_amount'];
                 if($buf['amount'] != 0)
                    $this->stock->in($buf);
            }
        } catch(Exception $e) {
            DB::rollback();
        }
        DB::commit();
      
        return redirect(route('stockAllotment.index'));
    }
}