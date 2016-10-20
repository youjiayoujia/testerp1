<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\SpuModel;
use DB;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;

class SpuModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id', 'spu','product_require_id','status','edit_user','image_user'];
    protected $guarded = [];

    public $searchFields = ['id' =>'ID','spu'=>'spu'];

    public function values()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function products()
    {
        return $this->hasMany('App\Models\ProductModel', 'spu_id', 'id');
    }

    public function editUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'edit_user');
    }

    public function imageEdit()
    {
        return $this->belongsTo('App\Models\UserModel', 'image_edit');
    }

    public function Purchase()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase');
    }

    public function Developer()
    {
        return $this->belongsTo('App\Models\UserModel', 'developer');
    }

    public function spuMultiOption()
    {
        return $this->hasMany('App\Models\Spu\SpuMultiOptionModel', 'spu_id');
    }

    public function productRequire()
    {
        return $this->hasMany('App\Models\Product\RequireModel', 'product_require_id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    /**
     * 更新多渠道多语言信息
     * 2016年6月3日10:43:18 YJ
     * @param array data 修改的信息
     */
    public function updateMulti($data)
    {   
        foreach ($data['info'] as $channel_id => $language) {
            $arr = [];
            $pre=[];
            $pre = $language['language'];
            foreach ($language as $prefix => $value) {
                $arr[$pre."_".$prefix] = $value;
            }
            
            $model = $this->spuMultiOption()->where("channel_id", $channel_id)->first();
            if($model){
                if($arr[$pre."_name"]!=''||$arr[$pre."_keywords"]!=''||$arr[$pre."_description"]!=''){
                    $model->update($arr);
                }   
            }
           
        }
    }

    public function test()
    {   
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        echo '<pre>';
        DB::table('items')->truncate();
        DB::table('products')->truncate();
        DB::table('spus')->truncate();

        $erp_products_data_arr = DB::select('select * from erp_products_data where spu!=""');
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
                    $spuModel = $this->create($spuData);
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
}