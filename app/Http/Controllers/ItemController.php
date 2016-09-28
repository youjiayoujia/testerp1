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
use App\Models\Product\ImageModel;
use App\Models\Product\SupplierModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\LimitsModel;
use App\Models\WrapLimitsModel;
use App\Models\CatalogModel;
use App\Models\UserModel;
use App\Models\Warehouse\PositionModel;
use Excel;
use App\Models\ChannelModel;
use App\Models\Item\SkuMessageModel;


class ItemController extends Controller
{
    public function __construct(ItemModel $item,SupplierModel $supplier,ProductModel $product,WarehouseModel $warehouse,LimitsModel $limitsModel,WrapLimitsModel $wrapLimitsModel, SkuMessageModel $message, ImageModel $imageModel)
    {
        $this->model     = $item;
        $this->supplier  = $supplier;
        $this->product   = $product;
        $this->warehouse = $warehouse;
        $this->message = $message;
        $this->image = $imageModel;
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
     * 获取供应商信息
     */
    public function ajaxSupplierUser()
    {
        $item_id = request()->input('item_id');
        $model = $this->model->find($item_id);
        $user_array = ItemModel::where('supplier_id',$model->supplier_id)->distinct()->get();
        $in = [];
        foreach ($user_array as $array) {
            $in[] = $array->purchase_adminer;
        }

        if(request()->ajax()) {
            $user = trim(request()->input('user'));
            $buf = UserModel::where('name', 'like', '%'.$user.'%')->whereIn('id',$in)->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }
        return json_encode(false);
    }

    /**
     * 更新采购员
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePurchaseAdmin($item_id)
    {
        $user_name = request()->input('manual_name');
        $user_id = request()->input('purchase_adminer');
        $model = $this->model->find($item_id);
        if($user_id){
            $model->update(['purchase_adminer'=>$user_id]);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
        }else{
            $userModel = UserModel::where('name',$user_name)->first();
            if($userModel){
                $model->update(['purchase_adminer'=>$userModel->id]);
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger','该用户不存在.'));
            }
        }

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
        if(!array_key_exists('productsVolume',$data)){
            foreach($skus as $itemModel){
                $itemModel->update($data);
            }       
            return redirect($this->mainIndex);
        }else{
            $data['length'] = $data['productsVolume']['bp']['length'];
            $data['width'] = $data['productsVolume']['bp']['width'];
            $data['height'] = $data['productsVolume']['bp']['height'];
            $data['package_length'] = $data['productsVolume']['ap']['length'];
            $data['package_width'] = $data['productsVolume']['ap']['width'];
            $data['package_height'] = $data['productsVolume']['ap']['height'];
            $data['weight'] = $data['products_weight2'];
            //echo '<pre>';
            //print_r($data);exit;
            foreach($skus as $itemModel){
                $itemModel->update($data);
            }       
            return redirect($this->mainIndex);
        }    
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
        $response['from'] = 'sku';
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
    //批量删除sku
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
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'Compute_channels' => ChannelModel::all(),

        ];
        return view($this->viewPath . 'index', $response);
    }

    public function question($item_id)
    {
        $content = request()->input('question_content');
        $question_group = request()->input('question_group');
        $data['sku_id'] = $item_id;
        $data['question_group'] = $question_group;
        $data['question'] = $content;
        $data['question_time'] = date('Y-m-d H:i:s',time());
        $data['question_user'] = request()->user()->id;
        $data['status'] = 'pending';
        $data['image'] = $this->image->skuMessageImage(request()->file('uploadImage'));
        $messageModel = $this->message->create($data);
        return redirect($this->mainIndex);
    }

    public function extraQuestion()
    {
        $content = request()->input('extra_content');
        $data['extra_question'] = $content;
        $id = request()->input('id');
        $sku_message = $this->message->find($id);
        $sku_message->update($data);
        return redirect(route('item.questionIndex'));
    }

    public function questionIndex()
    {
        request()->flash();
        $this->mainIndex = route('item.questionIndex');
        $this->mainTitle = '产品留言板';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->message),
            'mixedSearchFields' => $this->message->mixed_search,
        ];
        return view($this->viewPath . 'questionIndex', $response);
    }

    public function questionStatus()
    {
        $question_ids = request()->input('question_ids');
        $status = request()->input('status');
        $arr = explode(',', $question_ids); 
        
        foreach ($arr as $id) {
            $sku_message = $this->message->find($id);
            $sku_message->update(['status'=>$status]);
        }
        
        return 1;
    }

    public function answer()
    {
        $content = request()->input('answer_content');
        $id = request()->input('id');
        $sku_message = $this->message->find($id);
        $data['answer'] = $content;
        $data['answer_date'] = date('Y-m-d H:i:s',time());
        $data['answer_user'] = request()->user()->id;
        $data['status'] = 'close';
        $sku_message->update($data);
        return redirect(route('item.questionIndex'));
    }

    public function curlApiChangeWarehousePositon()
    {
        $data = request()->all();
        $positionModel = PositionModel::where('name',$data['products_location'])->get()->first();
        if(!$positionModel){
            echo json_encode('库位不存在');exit;
        }
        $itemModel = $this->model->where('sku',$data['products_sku'])->get()->first();
        if(!$itemModel){
            echo json_encode('sku不存在');exit;
        }
        $warehouse_position_id = $positionModel->id;
        $warehouse_id = $data['product_warehouse_id']==1000?1:2;
        $result = $itemModel->update(['warehouse_id'=>$warehouse_id,'warehouse_position'=>$warehouse_position_id]);
        echo json_encode('修改库位成功');exit;
    }

}