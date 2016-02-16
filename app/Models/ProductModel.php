<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\CatalogModel;
use App\Models\SpuModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
use App\Models\Product\ProductAttributeValueModel;
use App\Models\Product\ProductFeatureValueModel;
use App\Models\Product\SupplierModel;
use Illuminate\Support\Facades\DB;
use Tool;

class ProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    public $rules = [
        'create' => [
            'name' => 'required',
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
            'purchase_url' => 'url',
            'supplier_id' => 'required',
            'product_size' => 'required',
            'weight' => 'required|numeric',
            'upload_user' => 'required',
            'catalog_id' =>'required',
        ],
        'update' => [
            'name' => 'required',
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
            'purchase_url' => 'url',
            'product_size' => 'required',
            'weight' => 'required|numeric',
            'upload_user' => 'required',
            'catalog_id' =>'required',
        ]
    ];

    public $searchFields = ['name','id','c_name','model'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','model','name','c_name','alias_name','alias_cname','catalog_id','supplier_id','supplier_info','purchase_url','product_sale_url','purchase_price',
                            'purchase_carriage','product_size','package_size','weight','upload_user','assigner','default_image','carriage_limit',
                            'carriage_limit_1','package_limit','package_limit_1','status','remark','spu_id','second_supplier_id','supplier_sku'];



    public function image()
    {
        return $this->belongsTo('App\Models\Product\ImageModel','default_image');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel','catalog_id');
    }

    public function spu()
    {
        return $this->belongsTo('App\Models\SpuModel','spu_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel','supplier_id');
    }

    public function productAttributeValue()
    {      
        return $this->hasMany('App\Models\Product\ProductAttributeValueModel','product_id');
    }

    public function item()
    {      
        return $this->hasMany('App\Models\ItemModel','product_id');
    }
    
    /**
     * 获得辅助供应商
     * 2016-2-16 14:00:41 YJ
     * @param int $id 供应商ID
     * @return array
     */
    public function secondSupplierName($id)
    {
        $supplier = new SupplierModel();
        return $supplier::find($id)->name;
    }

    /**
     * jq获得产品属性
     * 2016-1-11 14:00:41 YJ
     * @param int $catalog_id,$product_id 品类及产品ID
     * @return array
     */
    public function getCatalogProperty($catalog_id) {  
        $catalog = CatalogModel::find($catalog_id);
        $set = ['Attributes', 'features'];
        $data = [];           
        $modelSet = $catalog->getModels();                        
        foreach($set as $models){
            $i = 0;
            foreach($catalog->$models as $model){
                $data[$models][$i]['name'] = $model->name;
                if($models=='features'){
                    $data[$models][$i]['type'] = $model->type;
                    $data[$models][$i]['feature_id'] = $model->id;
                }
                foreach($model->values as $key=>$value){
                    $data[$models][$i]['value'][] = $value->name; 
                }               
                $i++;
            }  
        }
        $data['models'] = $modelSet;
        //修改key值
        $data['attributes'] = $data['Attributes'];
        unset($data['Attributes']);
        return $data;
    }

    /**
     * jq获得产品属性
     * 2016-1-11 14:00:41 YJ
     * @param array $data,$files obj
     */
    public function createProduct($data='',$files=''){
        DB::beginTransaction();
        try {       
            //创建spu，,并插入数据
            $spumodel = new SpuModel();
            $spu = Tool::createSku();
            $spuarr['spu'] = $spu;
            $spuobj = $spumodel->create($spuarr);
            $data['spu_id'] = $spuobj->id;
            //获取catalog对象,将关联catalog的属性插入数据表
            $catalog = CatalogModel::find($data['catalog_id']);
            foreach($data['modelSet'] as $model){
                if(count($model)==1)continue;
                $data['model'] = $spu."-".$model['model'];;
                $product = $this->create($data);
                //获得productID,插入产品图片
                $data['product_id'] = $product->id;
                //默认s图片id为0
                $default_image_id = 0;
                $imageModel = new ImageModel();
                foreach($model['image'] as $key=>$file){
                    if($file!=''){
                        $image_id = $imageModel->singleCreate($data,$file,$key);
                        //获得首图的product_image_id
                        if($key=='image0'){
                            $default_image_id = $image_id;
                        }
                    }
                }
                //更新产品首图
                $product->update(['default_image'=>$default_image_id]);
                //插入产品attribute属性
                if(array_key_exists('attributes',$model)){
                    foreach($model['attributes'] as $attribute=>$attributeValues){              
                        $attributeModel = $catalog->Attributes()->where('name','=',$attribute)->get()->first();
                        foreach($attributeValues as $attributeValue){
                            $attributeValueModel = $attributeModel->values()->where('name','=',$attributeValue)->get()->first();   
                            $attributeArray['attribute_id'] =$attributeModel->id;
                            $attributeArray['attribute_value'] = $attributeValueModel->name;
                            $attributeArray['product_id'] = $product->id;
                            $productAttributeValueModel = new ProductAttributeValueModel();
                            $productAttributeValueModel->create($attributeArray);             
                        }
                    }                    
                }
            }
            //插入feature属性
            $keyset = ['featureradio','featurecheckbox','featureinput'];
            foreach($keyset as $key){
                if(array_key_exists($key, $data)){
                    foreach($data[$key] as $feature_id=>$feature_value){
                        $featureArray['feature_id'] = $feature_id;
                        $featureArray['spu_id'] = $spuobj->id;
                        if($key!='featureinput'){
                            foreach($feature_value as $value){
                                $featureArray['feature_value'] = $value;
                                $productFeatureValueModel = new ProductFeatureValueModel();
                                $productFeatureValueModel->create($featureArray);                        
                            }                        
                        }else{
                            $featureArray['feature_value'] = $feature_value;
                            $productFeatureValueModel = new ProductFeatureValueModel();
                            $productFeatureValueModel->create($featureArray);
                        }
                    }
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    /**
     * 更新product
     * 2016-1-13 17:48:26 YJ
     * @param $id int, $data array, $files obj
     */
    public function updateProduct($id,$data,$files = null){
        $product = $this->find($id);
        $spu_id = $product->spu_id;
        DB::beginTransaction();
        try {     
            //更新产品attribute属性
            if(array_key_exists('attributes',$data)){
                $productAttributeValueModel = new ProductAttributeValueModel();
                $attributes = $productAttributeValueModel->where('product_id',$id)->delete(); 
                foreach($data['attributes'] as $attribute_id=>$attribute_values){
                    $tmp = [];
                    $tmp['product_id'] = $id;
                    $tmp['attribute_id'] = $attribute_id;
                    foreach($attribute_values as $attribute_value){
                        $tmp['attribute_value'] = $attribute_value;
                        $model = new ProductAttributeValueModel();
                        $model->create($tmp);
                    }            
                }
            }
            //更新产品feature属性
            if(array_key_exists('features',$data)){
                $ProductFeatureValueModel = new ProductFeatureValueModel();
                $ProductFeatureValueModel->where('spu_id',$spu_id)->delete();
                foreach($data['features'] as $feature_id=>$feature_values){
                    $tmp = [];
                    $tmp['spu_id'] = $spu_id;
                    $tmp['feature_id'] = $feature_id;
                    if(is_array($feature_values)){
                        foreach($feature_values as $feature_value){
                            $tmp['feature_value'] = $feature_value;
                            $model = new ProductFeatureValueModel();
                            $model->create($tmp);
                        }                    
                    }else{
                        $tmp['feature_value'] = $feature_values;
                        $model = new ProductFeatureValueModel();
                        $model->create($tmp);
                    }
                     
                }             
            }
            //更新图片
            $data['product_id'] = $id;
            $data['spu_id'] = $spu_id;
            $data['type'] = 'original';
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
            
            /*foreach($files as $key=>$file){
                if($file!=''){
                    $image_id = $this->imageRepository->singleCreate($data,$file,$key);
                    if($key=='image0'){
                        $default_image_id = $image_id;
                    }
                }
                $data['default_image'] = $default_image_id;
            }*/           
            //更新基础信息
            $product->update($data);
        }catch (Exception $e) {
            DB::rollBack(); 
        }
        DB::commit();
    }

    /**
     * 创建item
     * 2016-1-13 17:48:26 YJ
     * @param array product_id_array 产品id字符串
     * @return array
     */
    public function createItem($product_id_array) {
        foreach($product_id_array as $product_id){
            $productModel = $this->find($product_id);
            $attributes = $productModel->productAttributeValue;
            $brr = [];
            foreach($attributes as $attribute){
                $brr[$attribute->attribute_id][] = $attribute->attribute_value;
            } 
            $brr = array_values($brr);
            //$result = $this->createDikaer($brr);
            $result = Tool::createDikaer($brr);
            $model = $productModel->model;
            foreach($result as $_result){
                $item = $model;
                foreach($_result as $__result){
                    $item .="-".$__result;
                }
                $product_data = $this->find($product_id)->toArray();
                $product_data['sku'] = $item;
                $product_data['product_id'] = $product_id;
                $item = new ItemModel();
                $item->create($product_data);         
            }
            $productModel->status = 1;
            $productModel->save();           
        }      
    }

}
