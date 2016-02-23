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
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;

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
            'allotments' =>$this->model->find($id)->allotmentform,
            'stockins' => InModel::where(['type'=>'ALLOTMENT', 'relation_id'=>$id])->with('stock')->get(),
            'stockouts' => OutModel::where(['type'=>'ALLOTMENT', 'relation_id'=>$id])->with('stock')->get(),
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
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.items_id')));
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
        $available_quantity = [];
        foreach($allotment as $key => $value) 
        {
            $obj = StockModel::where(['warehouses_id'=>$model->out_warehouses_id, 'items_id'=>$value->items->id])->get();
            $available_quantity[] =  $obj->first()->available_quantity;
            $buf = [];
            foreach($obj as $v)
            {   
                $buf[] = $v->position->toArray();
            }
            $arr[] = $buf;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $model,
            'warehouses' => WarehouseModel::all(),
            'skus' => StockModel::where(['warehouses_id'=>$model->out_warehouses_id])->distinct()->with('items')->get(),
            'positions' => $arr,
            'allotmentforms' => $allotment, 
            'availquantity' => $available_quantity,
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
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.items_id')));
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
        $obj = $this->model->find($id);
        foreach($obj->allotmentform as $val)
            $val->delete();
        $obj->delete();

        return redirect($this->mainIndex);
    }

    /**
     * 处理ajax请求参数,审核
     *
     * @param none
     * @return json|time
     *
     */
    public function ajaxAllotmentCheck()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $time = date('Y-m-d',time());       
            $obj = $this->model->find($id);
            $obj->update(['check_status'=>'Y', 'check_time'=>$time]); 
            echo json_encode($time);
        } else {
            echo json_encode('false');
        }
    }

    /**
     * 处理ajax请求，返回重新审核 
     *
     *  @param none
     *  @return any
     *
     */
    public function ajaxAllotmentNew()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $this->model->find($id)->update(['allotment_status'=>'new', 'check_status'=>'N', 'check_time'=>'0000-00-00']);
            return json_encode('111');
        }

        return json_encode('false');
    }

    /**
     * 处理ajax请求，确认出库 
     *
     *  @param none
     *  @return  any
     *
     */
    public function ajaxAllotmentCheckOut()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $obj = $this->model->find($id);
            $obj->update(['allotment_status'=>'out']);
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
            return json_encode('1221');
        }

        return json_encode('false');
    }

    /**
     *  处理ajax请求 
     *
     *  @param none
     *  @return view
     *
     */
    public function ajaxAllotmentAdd()
    {
        
            $current = request()->input('current');
            $warehouse = request()->input('warehouse');
            $sku_buf = StockModel::where('warehouses_id', $warehouse)->distinct()->with('items')->get()->toArray();
            $positions = StockModel::where(['warehouses_id'=>$warehouse, 'items_id'=>$sku_buf[0]['items']['id']])->get(); 
            $buf = [];
            foreach($positions as $position)
            {
                $tmp = $position->position->toArray();
                $buf[] = $tmp;
            }
            $response = [
                'skus' => $sku_buf,
                'positions' => $buf,
                'model' => $positions->first(),
                'current'=>$current,
            ];

            return view($this->viewPath.'add', $response);

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
        if(request()->ajax()) {
            $id = request()->input('id');
            $this->model->find($id)->update(['allotment_status'=>'pick']);
            return json_encode('11');
        }
        
        return json_encode('false');
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
            'positions' => PositionModel::where(['warehouses_id'=>$obj->in_warehouses_id])->get(),
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
            $len = count($arr['arr']['items_id']);
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
                $buf['items_id'] = ItemModel::where('sku',$buf['items_id'])->get()->first()->id;
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