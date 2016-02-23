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

use DB;
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
    public function __construct(AdjustmentModel $adjust)
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
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'adjustments' => $model->adjustment,
            'adjust' => $model,
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
        $len = count(array_keys(request()->input('arr.sku')));
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
            $buf['items_id'] = ItemModel::where('sku', $buf['sku'])->get()->first()->id;
            $buf['stock_adjustments_id'] = $obj->id;
            AdjustFormModel::create($buf);
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustment,
            'warehouses' => WarehouseModel::all(),
            'positions' =>PositionModel::where('warehouses_id', $model->warehouses_id)->get()->toArray(),
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
        $len = count(array_keys(request()->input('arr.sku')));
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
            $buf['items_id'] = ItemModel::where('sku', $buf['sku'])->get()->first()->id;
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
        foreach($obj->adjustment as $val)
            $val->delete();
        $obj->delete($id);

        return redirect($this->mainIndex);
    }

    /**
     * 
     *
     *
     */
    public function ajaxAdjustAdd()
    {
        $current = request()->input('current');
        $warehouse = request()->input('warehouse');
        $response = [
            'current' => $current,
            'positions' => PositionModel::where('warehouses_id', $warehouse)->get(),
        ];

        return view($this->viewPath.'add', $response);
    }

    /**
     * 处理ajax请求参数,审核
     *
     * @param none
     * @return json|time
     *
     */
    public function ajaxCheck()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $time = date('Y-m-d',time());       
            $obj = $this->model->find($id);
            $obj->update(['status'=>'Y', 'check_time'=>$time]); 
            echo json_encode($time);
            $obj->relation_id = $obj->id;
            $arr = $obj->toArray();
            $buf = $obj->adjustment->toArray();
            $stock = new StockModel;
            DB::beginTransaction();
            try {
                for($i=0;$i<count($buf);$i++) {

                    $tmp = array_merge($arr,$buf[$i]);
                    if($tmp['type'] == 'IN') {
                        $tmp['type'] = 'ADJUSTMENT';
                        $stock->in($tmp);
                    } else {
                        $tmp['type'] = 'ADJUSTMENT';

                        $stock->out($tmp);
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        }
    }
}