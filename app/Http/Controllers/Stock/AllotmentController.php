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
use App\Http\Controllers\Controller;
use App\Models\Stock\AllotmentModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\AllotmentFormModel;
use App\Models\Stock\OutRepository;
use App\Models\StockModel;
use App\Models\Stock\AllotmentLogisticsModel;

class AllotmentController extends Controller
{
    public function __construct(AllotmentModel $allotment)
    {
        $this->model = $allotment;
        $this->mainIndex = route('stockAllotment.index');
        $this->mainTitle = '库存调拨';
        $this->viewPath = 'stock.allotment.';
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
            'model' => $this->model->find($id),
            'allotmentforms' => $this->model->find($id)->allotmentform,
        ];
        
        return view($this->viewPath.'show', $response);
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
            'warehouses' => WarehouseModel::all(),
        ];

        return view($this->viewPath.'create', $response);
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
        request()->flash();
        $this->validate(request(), $this->rules(request()));
        $len = count(array_keys(request()->input('arr')['sku']));
        $buf = request()->all();
        $obj = $this->model->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_allotments_id'] = $obj->id;
            AllotmentFormModel::create($buf);
        }

        return redirect($this->mainIndex);
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
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $allotment = $model->allotmentform;
        $arr = [];
        foreach($allotment as $key => $value) 
        {
            $arr[] = StockModel::where(['warehouses_id'=>$model->out_warehouses_id, 'warehouse_positions_id'=>$value->warehouse_positions_id])->get(['sku'])->toArray();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $model,
            'warehouses' => WarehouseModel::all(),
            'positions' => PositionModel::where(['warehouses_id'=>$model->out_warehouses_id])->get(),
            'skus' => $arr,
            'allotmentforms' => $allotment, 
        ];

        return view($this->viewPath.'edit', $response);
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
        request()->flash();
        $this->validate(request(), $this->rules(request()));
        $len = count(array_keys(request()->input('arr')['sku']));
        $buf = request()->all();
        $obj = $this->model->find($id)->allotmentform;
        $obj_len = count($obj);
        $this->model->find($id)->update($buf);
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
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

        return redirect($this->mainIndex);
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
        $obj = $this->model->find($id)->allotmentform;
        foreach($obj as $val)
            $val->delete();
        $this->model->destroy($id);

        return redirect($this->mainIndex);
    }

    /**
     * 处理ajax请求参数,审核
     *
     * @param none
     * @return json|time
     *
     */
    public function ajaxAllotmentcheck()
    {
        if(request()->ajax()) {
            $id = $_GET['id'];
            $time = date('Y-m-d',time());       
            $obj = $this->model->find($id);
            $obj->update(['check_status'=>'Y', 'check_time'=>$time, 'allotment_status'=>'out']); 
            echo json_encode($time);
            
            $stock = new StockModel;
            $obj->relation_id = $obj->id;
            $arr = $obj->toArray();
            $buf = $obj->allotmentform->toArray();
            for($i=0;$i<count($buf);$i++) {
                $tmp = array_merge($arr, $buf[$i]);
                $tmp['warehouses_id'] = $tmp['out_warehouses_id'];
                $tmp['type'] = 'ALLOTMENT';
                $stock->out($tmp);
            }
        } else {
            echo json_encode('false');
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
            'out_warehouses_id' => 'required|integer',
            'in_warehouses_id' => 'required|integer',
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
            if($key == 'quantity')
                foreach($val as $k => $v)
                {
                    $arr['arr.quantity.'.$k] ='required|integer';
                }
            if($key == 'warehouse_positions_id')
                foreach($val as $k => $v)
                {
                    $arr['arr.warehouse_positions_id.'.$k] = 'required|integer';
                }
            if($key == 'amount')
                foreach($val as $k => $v)
                {
                    $arr['arr.amount.'.$k] = 'required';
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
        $this->model->find($id)->update(['allotment_status'=>'pick']);
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
        $position = new PositionModel;
        $obj = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $obj,
            'allotmentforms' => $obj->allotmentform,
            'warehouses' => WarehouseModel::all(),
            'positions' => $position->getObj(['warehouses_id'=>$obj->in_warehouses_id]),
        ];

        return view($this->viewPath.'checkform', $response);
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
        request()->flash();
        $arr = request()->all();
        $obj = $this->model->find($id)->allotmentform;
        DB::beginTransaction();
        try {
            $buf[] = $arr['arr']['new_receive_quantity'];
            $buf[] = $arr['arr']['warehouse_positions_id'];
            $buf[] = $arr['arr']['old_receive_quantity'];
            for($i=0; $i<count($buf[0]); $i++)
            {   
                $obj[$i]->update(['receive_quantity'=>($buf[0][$i]+$buf[2][$i]), 'in_warehouse_positions_id'=>$buf[1][$i]]);
            }
            $flag = 1;
            $buf[] = $arr['arr']['quantity'];
            for($i=0;$i<count($buf[3]);$i++)
            {
                if($buf[3][$i] != ($buf[0][$i] + $buf[2][$i]))
                    $flag = 0;
            }
            if($flag == 1)
            {
                $arr['allotment_status'] = 'over';
            } else {
                $arr['allotment_status'] = 'check';
            }

            $arr['checkform_time'] = date('Y-m-d',time());
            $this->model->find($id)->update(['allotment_status'=>$arr['allotment_status'], 'checkform_time'=>$arr['checkform_time'], 'remark'=>$arr['remark']]);

            $len = count($arr['arr']['item_id']);
            $stock = new StockModel;
            for($i=0; $i<$len; $i++)
            {
                $buf = [];
                foreach($arr['arr'] as $key => $value)
                {
                    $buf[$key] = $value[$i];
                }
                $buf = array_merge($buf,$arr);
                $buf['type'] = "ALLOTMENT";
                $buf['warehouses_id'] = $this->model->find($id)->in_warehouses_id;
                $buf['relation_id'] = $id;
                $buf['amount'] = round($buf['amount']/$buf['quantity']*$buf['new_receive_quantity'],3);
                $buf['quantity'] = $buf['new_receive_quantity'];
                if($buf['amount'] < 0)
                    throw new Exception('库存金额低于0了');
                if($buf['quantity'])
                    $stock->in($buf);
            }
        } catch(Exception $e) {
            DB::rollback();
        }
        DB::commit();
      
        return redirect($this->mainIndex);
    }
}