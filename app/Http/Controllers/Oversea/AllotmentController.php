<?php
/**
 * 海外仓头程物流控制器
 *
 * 2016-12.05
 * @author: MC<178069409>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\Allotment\AllotmentModel;
use App\Models\Oversea\Allotment\AllotmentFormModel;
use App\Models\WarehouseModel;
use App\Models\UserModel;
use App\Models\ItemModel;
use App\Models\StockModel;

class AllotmentController extends Controller
{
    public function __construct(AllotmentModel $allotment)
    {
        $this->model = $allotment;
        $this->mainIndex = route('overseaAllotment.index');
        $this->mainTitle = '调拨单';
        $this->viewPath = 'oversea.allotment.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'inWarehouses' => WarehouseModel::where(['type' => 'oversea', 'is_available' => '1'])->get(),
            'outWarehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available' => '1'])->get(),
        ];
        return view($this->viewPath . 'create', $response);
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
        $len = count(array_keys(request()->input('arr.item_id')));
        $name = UserModel::find(request()->user()->id)->name;
        $buf = request()->all();
        $buf['allotment_by'] = request()->user()->id;
        $obj = $this->model->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $arr[$key] = $val[$i];      
            }
            $arr['parent_id'] = $obj->id;
            AllotmentFormModel::create($arr);
            $item = ItemModel::find($arr['item_id']);
            $item->hold($arr['warehouse_position_id'], $arr['quantity'], 'ALLOTMENT', $obj->id);
        }
        $to = $this->model->with('allotmentForms')->find($obj->id);
        $to = json_encode($to);
        $this->eventLog($name, '新增调拨记录,id='.$obj->id, $to);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
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
            'allotments' =>$this->model->find($id)->allotmentForms,
        ];

        return view($this->viewPath.'show', $response);
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
        if(request()->ajax()) {
            $current = request()->input('current');
            $response = [
                'current'=>$current,
            ];
            return view($this->viewPath.'add', $response);
        }
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
        $allotment = $model->allotmentForms;
        $arr = [];
        $available_quantity = [];
        foreach($allotment as $key => $value) 
        {
            $obj = StockModel::where(['warehouse_id'=>$model->out_warehouse_id, 'item_id'=>$value->item_id])->get();
            $available_quantity[] =  StockModel::where(['warehouse_position_id'=>$value->warehouse_position_id, 'item_id'=>$value->item_id])->first()->available_quantity;
            $buf = [];
            foreach($obj as $v)
            {   
                $buf[] = $v->position->toArray();
            }
            $arr[] = $buf;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'outWarehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available'=>'1'])->get(),
            'inWarehouses' => WarehouseModel::where(['type' => 'oversea', 'is_available'=>'1'])->get(),
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
        $model = $this->model->with('allotmentForms')->find($id);
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $obj = $model->allotmentForms;
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($model);
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->unhold($value->warehouse_position_id, $value->quantity, 'ALLOTMENT', $model->id);
        }
        $obj_len = count($obj);
        $model->update($buf);
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['parent_id'] = $id;
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }
        $obj = $model->allotmentForms;
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->hold($value->warehouse_position_id, $value->quantity, 'ALLOTMENT', $model->id);
        }
        $to = json_encode($model);
        $this->eventLog($name, '调拨记录更新,id='.$model->id, $to, $from);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }
}