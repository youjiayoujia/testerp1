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

use App\Http\Controllers\Controller;
use App\Models\Stock\AdjustmentModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;
use App\Models\StockModel;
use App\Models\Stock\AdjustFormModel;

class AdjustmentController extends Controller
{
    public function __construct(AdjustFormModel $adjust)
    {
        $this->model = $adjust;
        $this->mainIndex = route('stockAdjustment.index');
        $this->mainTitle = '库存调整';
        $this->viewPath = 'stock.adjustment.';
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
            'adjustments' => $this->model->find($id)->adjustment,
            'adjust' => $this->model->find($id),
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
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {   
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['adjust_forms_id'] = $obj->id;
            AdjustmentModel::create($buf);
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
        $position = new PositionModel;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
            'adjustments' => $this->model->find($id)->adjustment,
            'warehouses' => WarehouseModel::all(),
            'positions' =>$position->getObj(['warehouses_id' => $this->model->find($id)->warehouses_id])->toArray(),
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
        $obj = $this->model->find($id)->adjustment;
        $obj_len = count($obj);
        $this->model->find($id)->update($buf);
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            $arr = request()->input('arr');
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
        $obj = $this->model->find($id)->adjustment;
        foreach($obj as $val)
            $val->delete();
        $this->destroy($id);

        return redirect($this->mainIndex);
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
        $obj = $this->model->find($id);
        $obj->update(['status'=>'Y', 'check_time'=>$time]); 
        echo json_encode($time);

        $obj->relation_id = $obj->adjust_form_id;
        $arr = $obj->toArray();
        $buf = $obj->adjustment->toArray();
        $stock = new StockModel;
        for($i=0;$i<count($buf);$i++) {
            $tmp = array_merge($arr,$buf[$i]);
            if($tmp['type'] == '入库') {
                $tmp['type'] = 'ADJUSTMENT';
                $stock->in($tmp);
            } else {
                $tmp['type'] = 'ADJUSTMENT';
                $stock->out($tmp);
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