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
        /*$model = new SpuModel();
        $model->test1();exit;
        $arr = array();
        $arr['c_name'] = '绒面一字扣防水台高跟鞋【白】【38码】';
        $arr['sku'] = 'SS4733A1';
        $arr['purchase_price'] = '261';
        $arr['supplier_id'] = '10001';
        $arr['purchase_url'] = 'http://www.ebay.com/itm/ws/eBayISAPI.dll?ViewItem&item=400386085083';
        $arr['dev_name'] = '曾杨顺';
        $arr['notify'] = '';
        $arr['spu'] = '12';
        $arr['model'] = '122';
        $arr['purchase_name'] = '张金平';
        $arr['status'] = 'cleaning';
        $arr['carriage_limit_arr'] = 'a:2:{i:0;i:1;i:1;i:4;}';
        $arr['package_limit_arr'] = 'a:0:{}';
        $arr['warehouse_id'] = '';
        $arr['type'] = 'edit';
        $url="http://www.yjerp.com/api/skuHandleApi";
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $arr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60); 
        $buf = curl_exec($c);
        print_r($buf);exit;
        $old_data = [];
        $old_data['sku'] = 'HJ1019W4';
        $old_data['name'] = '';
        $old_data['c_name'] = 'eee';
        $old_data['catalog_id'] = '';
        $old_data['declared_en'] = 'Eyeshadow';
        $old_data['declared_cn'] = '眼影';
        $old_data['declared_value'] = '2.00';
        $old_data['purchase_price'] = '2.5';
        $old_data['weight'] = '0.090';
        $old_data['package_weight'] = '0';
        $old_data['supplier_id'] = '13700';
        $old_data['quality_standard'] = '1.检查标签sku是否正确; 2.检查跟上一批次是否一致; 3.外观污染破损检查';
        $old_data['warehouse_id'] = '1';
        $old_data['purchase_url'] = 'http://detail.1688.com/offer/37363914469.html?spm=0.0.0.0.Z9JcM6';
        $old_data['product_require_id'] = '';
        $old_data['warehouse_position'] = 'B13-01-03';
        $old_data['notify'] = '';
        $old_data['unit'] = '千克/件';
        $old_data['status'] = 'stopping';
        $old_data['type'] = 'add';
        $arr = [];
        $arr[] = '2';
        $arr[] = '3';
        $brr = [];
        $brr[] = '2';
        $brr[] = '4';
        $old_data['carriage_limit_arr'] = serialize($arr);
        $old_data['package_limit_arr'] = serialize($brr);

        //$old_data = json_encode($old_data);
       //echo '<pre>';
//print_r($old_data);exit; 
        $url="http://v3.erp.moonarstore.com/api/skuHandleApi";
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $old_data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60); 
        $buf = curl_exec($c);
        print_r($buf);exit;
        exit;*/
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
        $model = $this->model->find($data['spu_id']);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        $model->update(['remark'=>$data['remark']]);
        $to = json_encode($model);
        $this->eventLog($userName->name, '备注更新,id='.$model->id, $to, $from);
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
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($spuModel);
        $spuModel->updateMulti($data);
        $to = json_encode($spuModel);
        $this->eventLog($userName->name, '小语言信息更新,id='.$spuModel->id, $to, $from);
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
        /*ini_set('memory_limit','2048M');
        $last_id = SpuMultiOptionModel::all()->last()->spu_id;
        $spus = $this->model->where('id','>',$last_id)->get();
        foreach ($spus as $spu) {
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>1]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>2]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>3]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>4]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>5]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>6]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>7]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>8]);
        }*/
    }

    public function insertData()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'insertindex', $response);

    }

    public function uploadSku()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
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
                
                $itemModel = ItemModel::where('sku',$value['1'])->first();
                $arr = [];
                $arr[] = $value['2'];
                if(count($itemModel)){
                    $itemModel->product->logisticsLimit()->sync($arr);
                }
            }
            
            /*foreach ($data_array as $key => $value) {
                $itemModel = ItemModel::where('sku',$value['1'])->first();
                if(count($itemModel)){
                    $catalogModel = CatalogModel::where('c_name',$value['3'])->first();

                    if(count($catalogModel)){

                        $itemModel->update(['catalog_id'=>$catalogModel->id]);
                        $itemModel->product->update(['catalog_id'=>$catalogModel->id]);
                    }
                }
                
            }*/
            /*foreach ($data_array as $key => $value) {
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

                
            }*/
        },'gb2312');        
    }

    public function spuTemp()
    {
        $itemModel = new ItemModel();
        $itemModel->updateOldData();
    }
}
