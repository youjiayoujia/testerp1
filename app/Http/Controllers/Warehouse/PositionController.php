<?php
/**
 * 库位控制器
 * 处理库位相关的Request与Response
 *
 * @author: MC
 * Date: 15/12/18
 * Time: 16:15pm
 */

namespace App\Http\Controllers\warehouse;

use Excel;
use App\Http\Controllers\Controller;
use App\Models\Warehouse\PositionModel;
use App\Models\WarehouseModel;

class PositionController extends Controller
{
    protected $warehouse;

    public function __construct(PositionModel $position)
    {
        $this->model = $position;
        $this->mainIndex = route('warehousePosition.index');
        $this->mainTitle = '库位';
        $this->viewPath = 'warehouse.position.';
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return none
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::where(['is_available' => '1'])->get(),
        ];

        return view($this->viewPath.'create', $response);
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
            'warehouses' => WarehouseModel::where(['is_available' => '1'])->get(),
            'model' => $this->model->find($id),
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
        if(request()->input('is_available') == '0')
        {
            if($model->stocks) {
                $stocks = $model->stocks;
                foreach($stocks as $stock) {
                    $stock->delete();
                }
            }
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($model->stocks) {
            $stocks = $model->stocks;
            foreach($stocks as $stock) {
                $stock->delete();
            }
        }
        $model->destroy($id);
        
        return redirect($this->mainIndex);
    }

    /**
     * 获取库位信息,return 
     *
     * @param none
     * @return json
     *
     */
    public function ajaxGetPosition()
    {
        if(request()->ajax()) {
            $warehouse_id = request()->input('val');
            $buf = $this->model->where(['is_available'=>'1', 'warehouse_id'=>$warehouse_id])->get();
            if($buf)
                return json_encode($buf);
            else
                return json_encode('none');
        }
        
        return json_encode('false');
    }

    /**
     * 获取库位信息,return 
     *
     * @param none
     * @return json
     *
     */
    public function ajaxCheckPosition()
    {
        if(request()->ajax()) {
            $position = trim(request()->input('position'));
            $buf = $this->model->where(['is_available'=>'1', 'name'=>$position])->first();
            if($buf)
                return json_encode(true);
            else
                return json_encode(false);
        }
        
        return json_encode(false);
    }

    /**
     * 获取excel表格 
     *
     * @param none
     *
     */
    public function getExcel()
    {
        $rows = [
                    [ 
                     'name'=>'',
                     'warehouse'=>'',
                     'remark'=>'',
                     'size'=>'',
                     'length'=>'',
                     'width'=>'',
                     'height'=>'',
                     'is_available'=>'',
                     '备注'=>'warehouse正常的仓库名,size:big/middle/small 对应大中小,is_available:0/1 是否启用 0=否'
                    ]
            ];
        $name = 'warehouse_position';
        Excel::create($name, function($excel) use ($rows){
            $nameSheet='库位';
            $excel->sheet($nameSheet, function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * excel 导入数据
     *
     * @param
     *
     */
    public function importByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcess()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $errors = $this->model->excelProcess($file);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];
            return view($this->viewPath.'excelResult', $response);
        }
    }
}