<?php
/**
 * spu管理控制器
 * @author: youjia
 * Date: 2016-8-2 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\SpuModel;
use App\Models\CatalogModel;
use App\Models\Warehouse\PositionModel;
use App\Models\ProductModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\ChannelModel;
use App\Models\Spu\SpuMultiOptionModel;
use Excel;
use DB;

class SpuController extends Controller
{
    public function __construct(SpuModel $spu)
    {
        $this->model = $spu;
        $this->mainIndex = route('spu.index');
        $this->mainTitle = 'SPU列表';
        $this->viewPath = 'spu.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        foreach(config('spu.status') as $key=>$value){
        	$num_arr[$key] = $this->model->where('status',$key)->count();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$this->model->with('Purchase','editUser','imageEdit','Developer')),
            'num_arr' =>$num_arr,
            'users' => UserModel::all(),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

     /**
     * 分配任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dispatchUser()
    {
        $user_id = request()->input("user_id");
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach($arr as $id){
        	$this->model->find($id)->update([$action=>$user_id]);
        }	
        return 1;
    }

    /**
     * 处理任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doAction()
    {
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach($arr as $id){
        	$this->model->find($id)->update(['status'=>$action]);
        }	
        return 1;
    }

    /**
     * 退回
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionBack()
    {
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach(config("spu.status") as $key=>$value){
            if($key==$action){
                $action = config("spu.status")[$key];break;
            }
            $prev_action = $key;
        }
        
        foreach($arr as $id){
            $this->model->find($id)->update(['status'=>$prev_action]);
        }   
        return 1;
    }

    /**
     * 保存备注
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saveRemark()
    {
        $data = request()->all();
        $this->model->find($data['spu_id'])->update(['remark'=>$data['remark']]);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '备注添加成功'));
    }

    /**
     * 小语言编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function spuMultiEdit()
    {

        $data = request()->all();
        $language = config('product.multi_language');
        $model = $this->model->find($data['id']);
        $default = $model->spuMultiOption->where("channel_id",ChannelModel::all()->first()->id)->first()->toArray();
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' =>$this->model->find($data['id']),
            'languages' => config('product.multi_language'),
            'channels' => ChannelModel::all(),
            'id' => $data['id'],
            'default' =>$default,
        ];
        return view($this->viewPath . 'language', $response);
    }

    /**
     * 小语言更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function spuMultiUpdate()
    {
        $data = request()->all();
        //echo '<pre>';
        //print_r($data);exit;
        $spuModel = $this->model->find($data['spu_id']);
        $spuModel->updateMulti($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spuInfo()
    {
        $channel_id = request()->input("channel_id");
        $language = request()->input("language");
        $spu_id = request()->input("spu_id");
        $model = $this->model->find($spu_id);
        $info = $model->spuMultiOption()->where("channel_id",$channel_id)->first()->toArray();
        $result['name'] = $info[$language."_name"];
        $result['description'] = $info[$language."_description"];
        $result['keywords'] = $info[$language."_keywords"];
        return $result;
    }

    /**
     * 小语言
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insertLan()
    {
        $spus = $this->model->all();

        foreach ($spus as $spu) {
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>1]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>2]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>3]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>4]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>5]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>6]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>7]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>8]);
        }
    }

    public function insertData()
    {
        /*$response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'insertindex', $response);*/
set_time_limit(0);
ini_set('memory_limit', '1024M');
echo '<pre>';
DB::table('items')->truncate();
DB::table('products')->truncate();
DB::table('spus')->truncate();

        $erp_products_data_arr = DB::select('select * from erp_products_data where spu!="" limit 1000');
        foreach ($erp_products_data_arr as $key => $erp_products_data) {
            //print_r($erp_products_data);exit;
                //if($key==0)continue;
                //print_r($value);exit;
                if(count(ItemModel::where('sku',$erp_products_data->products_sku)->get()))continue;
                if($erp_products_data->products_location!=''){
                    $position['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';
                    $position['name'] = $erp_products_data->products_location;
                    $position['is_available'] = 1;
                    if(!count(PositionModel::where('name',$erp_products_data->products_location)->get())){
                        PositionModel::create($position);
                    }
                }
                
                $spuData['spu'] = $erp_products_data->spu;
                //创建spu
                if(count(SpuModel::where('spu',$erp_products_data->spu)->get())){
                    $spu_id = SpuModel::where('spu',$erp_products_data->spu)->get()->toArray()[0]['id'];
                }else{
                    $spuModel = $this->model->create($spuData);
                    $spu_id = $spuModel->id;
                }
                

                $productData['model'] = $erp_products_data->model;
                $productData['spu_id'] = $spu_id;
                $productData['name'] = $erp_products_data->products_name_en;
                $productData['c_name'] = $erp_products_data->products_name_cn;
                //$catalog_id = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                //print_r($catalog_id);exit;
                //$productData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $productData['supplier_id'] = $erp_products_data->products_suppliers_id;
                
                $productData['purchase_url'] = $erp_products_data->products_more_img;
                //$productData['purchase_day'] = $value['10'];
                //$productData['product_sale_url'] = $value['11'];
                $productData['notify'] = $erp_products_data->products_warring_string;
                //采购价
                $productData['purchase_price'] = $erp_products_data->products_value;
                $productData['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';

                $volume = unserialize($erp_products_data->products_volume);
                $productData['package_height'] = $volume['ap']['length'];
                $productData['package_width'] = $volume['ap']['width'];
                $productData['package_length'] = $volume['ap']['height'];
                $productData['height'] = $volume['bp']['length'];
                $productData['width'] = $volume['bp']['width'];
                $productData['length'] = $volume['bp']['height'];
                //创建model
                if(count(ProductModel::where('model',$erp_products_data->model)->get())){
                    $product_id = ProductModel::where('model',$erp_products_data->model)->get()->toArray()[0]['id'];
                }else{
                    $productModel = ProductModel::create($productData);
                    $product_id = $productModel->id;
                    if($erp_products_data->pack_method!=''){
                        $wrr['wrap_limits_id'] = $erp_products_data->pack_method;
                        $productModel->wrapLimit()->attach($wrr); 
                    }
                }

                $skuData['product_id'] = $product_id;
                //$skuData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $skuData['sku'] = $erp_products_data->products_sku;
                $skuData['name'] = $erp_products_data->products_name_en;
                $skuData['c_name'] = $erp_products_data->products_name_cn;
                $skuData['weight'] = $erp_products_data->products_weight;
                $skuData['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';
                $skuData['warehouse_position'] = $erp_products_data->products_location;
                $skuData['supplier_id'] = $erp_products_data->products_suppliers_id;
                $skuData['purchase_url'] = $erp_products_data->products_more_img;
                $skuData['purchase_price'] = $erp_products_data->products_value;
                //$skuData['purchase_adminer'] = $value['22'];
                $skuData['cost'] = $erp_products_data->products_value;

                $skuData['height'] = $volume['bp']['height'];
                $skuData['width'] = $volume['bp']['width'];
                $skuData['length'] = $volume['bp']['length'];
                $skuData['package_height'] = $volume['ap']['height'];
                $skuData['package_width'] = $volume['ap']['width'];
                $skuData['package_length'] = $volume['ap']['length'];
                
                $skuData['status'] =$erp_products_data->products_status_2;
                $skuData['is_available'] = $erp_products_data->productsIsActive; 
                //$skuData['remark'] = $value['41'];
                //创建sku
                $itemModel = ItemModel::create($skuData);
                foreach(explode(',',$erp_products_data->products_suppliers_ids) as $_supplier_id){
                    //print_r($itemModel->skuPrepareSupplier());exit;
                    $arr['supplier_id'] = $_supplier_id;
                    $itemModel->skuPrepareSupplier()->attach($arr);
                }

                
            }
    }

    public function uploadSku()
    {
        set_time_limit(0);
        $file = request()->file('upload');
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $data_array = '';
        $result = false;
        //echo '<pre>';
        Excel::load($path.'excelProcess.xls', function($reader) use (&$result) {
            $reader->noHeading();
            $data_array = $reader->all()->toArray();
            
            foreach ($data_array as $key => $value) {
                if($key==0)continue;
                //print_r($value);exit;
                if(count(ItemModel::where('sku',$value['3'])->get()))continue;
                if($value['15']!=''){
                    $position['warehouse_id'] = $value['14']==1000?'1':'2';
                    $position['name'] = $value['15'];
                    $position['is_available'] = 1;
                    if(!count(PositionModel::where('name',$value['15'])->get())){
                        PositionModel::create($position);
                    }
                }
                
                $spuData['spu'] = $value['1'];
                //创建spu
                if(count(SpuModel::where('spu',$value['1'])->get())){
                    $spu_id = SpuModel::where('spu',$value['1'])->get()->toArray()[0]['id'];
                    //print_r($spu_id);exit;
                }else{
                    $spuModel = $this->model->create($spuData);
                    $spu_id = $spuModel->id;
                }
                

                $productData['model'] = $value['2'];
                $productData['spu_id'] = $spu_id;
                $productData['name'] = $value['5'];
                $productData['c_name'] = $value['4'];
                //$catalog_id = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                //print_r($catalog_id);exit;
                //$productData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $productData['supplier_id'] = $value['7'];
                $productData['purchase_url'] = $value['9'];
                $productData['purchase_day'] = $value['10'];
                $productData['product_sale_url'] = $value['11'];
                //采购价
                $productData['purchase_price'] = $value['12'];
                //$productData['warehouse_id'] = $value['14']==1000?'1':'2';
                $productData['package_height'] = $value['21'];
                $productData['package_width'] = $value['20'];
                $productData['package_length'] = $value['19'];
                $productData['height'] = $value['18'];
                $productData['width'] = $value['17'];
                $productData['length'] = $value['16'];
                //创建model
                if(count(ProductModel::where('model',$value['2'])->get())){
                    $product_id = ProductModel::where('model',$value['2'])->get()->toArray()[0]['id'];
                }else{
                    $productModel = ProductModel::create($productData);
                    $product_id = $productModel->id;
                    if($value['39']!=''){
                        $wrr['wrap_limits_id'] = $value['39'];
                        $productModel->wrapLimit()->attach($wrr); 
                    }
                }

                $skuData['product_id'] = $product_id;
                //$skuData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $skuData['sku'] = $value['3'];
                $skuData['name'] = $value['5'];
                $skuData['c_name'] = $value['4'];
                $skuData['weight'] = $value['25'];
                $skuData['warehouse_id'] = $value['14']==1000?'1':'2';
                $skuData['warehouse_position'] = $value['15'];
                $skuData['supplier_id'] = $value['7'];
                $skuData['purchase_url'] = $value['9'];
                $skuData['purchase_price'] = $value['12'];
                $skuData['purchase_adminer'] = $value['22'];
                $skuData['cost'] = $value['13'];
                $skuData['height'] = $value['18'];
                $skuData['width'] = $value['17'];
                $skuData['length'] = $value['16'];
                $skuData['package_height'] = $value['21'];
                $skuData['package_width'] = $value['20'];
                $skuData['package_length'] = $value['19'];
                $skuData['status'] = $value['29'];
                $skuData['is_available'] = $value['40'];
                $skuData['remark'] = $value['41'];
                //创建sku
                $itemModel = ItemModel::create($skuData);
                foreach(explode(',',$value['8']) as $_supplier_id){
                    //print_r($itemModel->skuPrepareSupplier());exit;
                    $arr['supplier_id'] = $_supplier_id;
                    $itemModel->skuPrepareSupplier()->attach($arr);
                }

                
            }
        },'gb2312');        
    }

}
