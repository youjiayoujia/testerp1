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
use Exception;
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
        $this->middleware('StockIOStatus');
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
        $adjustmentforms = $model->adjustments;
        $access_quantity = [];
        foreach($adjustmentforms as $key => $adjustmentform)
        {
            $stock = StockModel::where(['item_id' => $adjustmentform->item_id, 'warehouse_position_id' => $adjustmentform->warehouse_position_id])->first();
            if($stock) {
                $access_quantity[] = $stock->available_quantity;
            } else {
                $access_quantity[] = 0;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'positions' =>PositionModel::where(['warehouse_id' => $model->warehouse_id, 'is_available' => '1'])->get()->toArray(),
            'access_quantity' => $access_quantity,
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
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
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
            $buf['item_id'] = ItemModel::where('sku', $buf['sku'])->first()->id;
            $buf['stock_adjustment_id'] = $obj->id;
            $buf['amount'] = $buf['quantity'] * $buf['unit_cost'];
            $buf['warehouse_position_id'] = PositionModel::where(['is_available'=>'1', 'name'=>trim($buf['warehouse_position_id'])])->first()->id;
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
        $adjustmentforms = $model->adjustments;
        $access_quantity = [];
        foreach($adjustmentforms as $key => $adjustmentform)
        {
            $stock = StockModel::where(['item_id' => $adjustmentform->item_id, 'warehouse_position_id' => $adjustmentform->warehouse_position_id])->first();
            if($stock) {
                $access_quantity[] = $stock->available_quantity;
            } else {
                $access_quantity[] = 0;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'positions' =>PositionModel::where(['warehouse_id' => $model->warehouse_id, 'is_available' => '1'])->get()->toArray(),
            'access_quantity' => $access_quantity,
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
        $obj = $this->model->find($id)->adjustments;
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
            $buf['items_id'] = ItemModel::where('sku', $buf['sku'])->first()->id;
            $buf['amount'] = $buf['quantity'] * $buf['unit_cost'];
            $buf['warehouse_position_id'] = PositionModel::where(['is_available'=>'1', 'name'=>trim($buf['warehouse_position_id'])])->first()->id;
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
        foreach($obj->adjustments as $val)
            $val->delete();
        $obj->delete($id);

        return redirect($this->mainIndex);
    }

    /**
     * 新增一个条目
     * 
     * @param current int warehouse 仓库
     * @return view
     *
     */
    public function ajaxAdjustAdd()
    {
        if(request()->ajax()) {
            $current = request()->input('current');
            $warehouse = request()->input('warehouse');
            $response = [
                'current' => $current,
                'positions' => PositionModel::where(['warehouse_id' => $warehouse, 'is_available' => '1'])->get(),
            ];

            return view($this->viewPath.'add', $response);
        }
    }

    public function check($id)
    {
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'positions' =>PositionModel::where(['warehouse_id' => $model->warehouse_id, 'is_available' => '1'])->get()->toArray(),
        ];

        return view($this->viewPath.'check', $response);
    }

    public function checkResult($id)
    {
        $result = request('result');
        $obj = $this->model->find($id);
        if($result) {       
            
            $obj->update(['status'=>'2', 'check_time'=>date('Y-m-d h:i:s'), 'check_by'=>'2']); 
            $obj->relation_id = $obj->id;
            $arr = $obj->toArray();
            $buf = $obj->adjustments->toArray();
            DB::beginTransaction();
            try {
                for($i=0;$i<count($buf);$i++) {
                    $tmp = array_merge($arr,$buf[$i]);
                    $item = ItemModel::find($tmp['item_id']);
                    if($tmp['type'] == 'IN') {
                        $tmp['type'] = 'ADJUSTMENT';
                        $item->in($tmp['warehouse_position_id'], $tmp['quantity'], $tmp['amount'], $tmp['type'], $tmp['relation_id'], $tmp['remark']);
                    } else {
                        $tmp['type'] = 'ADJUSTMENT';
                        $item->out($tmp['warehouse_position_id'], $tmp['quantity'], $tmp['type'], $tmp['relation_id'], $tmp['remark']);
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        } else {
            $obj->update(['status'=>'1', 'check_time'=>date('Y-m-d h:i:s'), 'check_by'=>'2']);
        }
        return redirect($this->mainIndex);
    }
}