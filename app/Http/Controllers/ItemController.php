<?php
/**
 * item控制器
 *
 * User: youjia
 * Date: 16/1/18
 * Time: 09:32:00
 */

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\LimitsModel;
use App\Models\WrapLimitsModel;
use App\Models\CatalogModel;
use Excel;

class ItemController extends Controller
{
    public function __construct(ItemModel $item,SupplierModel $supplier,ProductModel $product,WarehouseModel $warehouse,LimitsModel $limitsModel,WrapLimitsModel $wrapLimitsModel)
    {
        $this->model     = $item;
        $this->supplier  = $supplier;
        $this->product   = $product;
        $this->warehouse = $warehouse;
        $this->logisticsLimit = $limitsModel;
        $this->wrapLimit = $wrapLimitsModel;
        $this->mainIndex = route('item.index');
        $this->mainTitle = '产品SKU';
        $this->viewPath  = 'item.';
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logisticsLimit_arr = [];
        foreach($model->product->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['pivot']['logistics_limits_id'];              
        }
        $wrapLimit_arr = [];
        foreach($model->product->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['pivot']['wrap_limits_id'];               
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'suppliers' => $this->supplier->all(),
            'warehouses' => $this->warehouse->where('type','local')->get(),
            'wrapLimit' => $this->wrapLimit->all(),
            'logisticsLimit' => $this->logisticsLimit->all(),
            'wrapLimit_arr' => $wrapLimit_arr,
            'logisticsLimit_arr' => $logisticsLimit_arr,
        ];
        return view($this->viewPath . 'edit', $response);
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
        $model->updateItem(request()->all());
        return redirect($this->mainIndex);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logisticsLimit_arr = [];
        foreach($model->product->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['name'];              
        }
        
        $wrapLimit_arr = [];
        foreach($model->product->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['name'];               
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'warehouse' => $this->warehouse->find($model->warehouse_id),
            'logisticsLimit_arr' => $logisticsLimit_arr,
            'wrapLimit_arr' => $wrapLimit_arr,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 批量更新界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batchEdit()
    {
        $item_ids = request()->input("item_ids");
        $arr = explode(',', $item_ids);
        $param = request()->input('param');
        
        $skus = $this->model->whereIn("id",$arr)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'skus' => $skus,
            'item_ids'=>$item_ids,
            'param'  =>$param,
            'catalogs'=>CatalogModel::all(),
        ];
        return view($this->viewPath . 'batchEdit', $response);
    }

    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batchUpdate()
    {
        $item_ids = request()->input("item_ids");
        $arr = explode(',', $item_ids);
        $skus = $this->model->whereIn("id",$arr)->get();
        $data = request()->all();
        foreach($skus as $itemModel){
            $itemModel->update($data);
        }       
        return redirect($this->mainIndex);
    }


    public function getImage()
    {
        $item = $this->model->where('sku',trim(request('sku')))->first();
        if(!$item) {
            return json_encode(false);
        }
        $image = $item->product->image->path;
        $name = $item->product->image->name;
        if($image)
            return ('/'.$image.'/'.$name);
        else 
            return json_encode(false);
    }

    public function getModel()
    {
        $sku = trim(request('sku'));
        $model = $this->model->where('sku', $sku)->first();
        return json_encode($model);
    }

    /**
     * 打印产品
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printsku()
    {
        $item_id = request()->input("id");
        $model = $this->model->find($item_id);
        $response['model']= $model;
        return view($this->viewPath . 'printsku', $response);
    }

    /**
     * 上传表格修改sku状态
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadSku()
    {
        $file = request()->file('upload');
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $data_array = '';
        $result = false;
        Excel::load($path.'excelProcess.xls', function($reader) use (&$result) {
            $reader->noHeading();
            $data_array = $reader->all()->toArray();
            foreach ($data_array as $key => $value) {
                if($key==0)continue;
                if($this->model->where('sku',$value['1'])->first()){
                    $this->model->where('sku',$value['1'])->first()->update(['status'=>$value['2']]);
                }else{
                    $result = $key;
                }
                
            }
        },'gb2312');

        if($result){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '第'.$result."行SKU不存在，请重新上传"));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '状态修改成功.')); 
        }
        
    }

    public function batchDelete()
    {
        $item_ids = request()->input('item_ids');
        $item_ids = explode(',', $item_ids);
        foreach ($item_ids as $key => $item_id) {
            $model = $this->model->find($item_id);
            if (!$model) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
            }
            $model->destroy($item_id);
        }

        return 1;
    }
}