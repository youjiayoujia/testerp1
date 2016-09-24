<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Product\ImageModel;
use App\Models\Product\RequireModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;
use App\Models\ChannelModel;
use Storage;

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
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
            'supplier_id' => 'required',
            'product_size' => 'required',
            'weight' => 'required|numeric',
            //'catalog_id' => 'required',
        ],
        'update' => [
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
            'product_size' => 'required',
            'weight' => 'required|numeric',
        ]
    ];

    public $searchFields = ['name'=>'英文名', 'id'=>'id', 'c_name'=>'中文名', 'model'=>'model'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'model',
        'name',
        'c_name',
        'alias_name',
        'alias_cname',
        'catalog_id',
        'supplier_id',
        'supplier_info',
        'purchase_url',
        'url1',
        'url2',
        'url3',
        'product_sale_url',
        'purchase_price',
        'purchase_carriage',
        'product_size',
        'package_size',
        'weight',
        'warehouse_id',
        'size_description',
        'description',
        'upload_user',
        'edit_user',
        'edit_image_user',
        'examine_user',
        'revocation_user',
        'purchase_adminer',
        'default_image',
        'carriage_limit',
        'carriage_limit_1',
        'package_limit',
        'package_limit_1',
        'status',
        'edit_status',
        'examine_status',
        'quality_standard',
        'remark',
        'image_edit_not_pass_remark',
        'data_edit_not_pass_remark',
        'spu_id',
        'second_supplier_id',
        'supplier_sku',
        'second_supplier_sku',
        'purchase_day',
        'parts',
        'declared_cn',
        'declared_en',
        'notify',
        'declared_value',
        'package_height',
        'package_width',
        'package_length',
        'height',
        'width',
        'length',
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['supplier' => ['name'], 'catalog' => ['name']],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Product\ImageModel', 'default_image');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id');
    }

    public function spu()
    {
        return $this->belongsTo('App\Models\SpuModel', 'spu_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function secondSupplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'second_supplier_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'upload_user');
    }

    public function purchaseAdminer()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_adminer');
    }

    public function item()
    {
        return $this->hasMany('App\Models\ItemModel', 'product_id');
    }

    public function logisticsLimit()
    {
        return $this->belongsToMany('App\Models\Logistics\LimitsModel','product_logistics_limits','product_id','logistics_limits_id')->withTimestamps();
    }

    public function wrapLimit()
    {
        return $this->belongsToMany('App\Models\WrapLimitsModel','product_wrap_limits','product_id','wrap_limits_id')->withTimestamps();
    }

    public function variationValues()
    {
        return $this->belongsToMany('App\Models\Catalog\VariationValueModel', 'product_variation_values', 'product_id',
            'variation_value_id')->withTimestamps();
    }

    public function clearance()
    {
        return $this->hasOne('App\Models\CustomsClearanceModel', 'product_id', 'id');
    }

    public function featureValues()
    {
        return $this->belongsToMany('App\Models\Catalog\FeatureValueModel', 'product_feature_values', 'product_id',
            'feature_value_id')->withTimestamps();
    }

    public function featureTextValues()
    {
        return $this->hasMany('App\Models\Product\ProductFeatureValueModel', 'product_id');
    }

    public function amazonProduct()
    {
        return $this->hasOne('App\Models\Product\channel\amazonProductModel', 'product_id');
    }

    public function ebayProduct()
    {
        return $this->hasOne('App\Models\Product\channel\ebayProductModel', 'product_id');
    }

    public function aliexpressProduct()
    {
        return $this->hasOne('App\Models\Product\channel\aliexpressProductModel', 'product_id');
    }

    public function b2cProduct()
    {
        return $this->hasOne('App\Models\Product\channel\b2cProductModel', 'product_id');
    }

    public function productEnglishValue()
    {
        return $this->hasOne('App\Models\Product\ProductEnglishValueModel', 'product_id');
    }

    public function imageAll()
    {
        return $this->hasMany('App\Models\Product\ImageModel', 'product_id');
    }

    public function productMultiOption()
    {
        return $this->hasMany('App\Models\Product\ProductMultiOptionModel', 'product_id');
    }

    public function getDimageAttribute()
    {
        $arr = array('1'=>'8','2'=>'9');
        if (count($this->imageAll)) {
            foreach ($this->imageAll as $key => $image) {
                $temp = [];
                foreach ($image->labels as  $label) {
                    $temp[] = $label->pivot->label_id;
                }
                if(count(array_intersect($arr,$temp))==2){
                    return $image->path . $image->name;
                }
            }    
        }
        return '/default.jpg';
    }

    /**
     * 创建产品
     * 2016-1-11 14:00:41 YJ
     * @param array $data ,$files obj
     */
    public function createProduct($data = '', $files = '')
    {
        DB::beginTransaction();
        try {
            //获取catalog对象,将关联catalog的属性插入数据表
            $catalog = CatalogModel::find($data['catalog_id']);
            //$code_num = SpuModel::where("spu", "like", $catalog->code . "%")->get()->count();
            
            //创建spu，,并插入数据
            //$spuobj = SpuModel::create(['spu' => Tool::createSku($catalog->code, $code_num)]);
            $spuobj = SpuModel::where('product_require_id',$data['require_id'])->get()->first();
            
            $data['purchase_adminer'] = $spuobj->purchase;
            $data['spu_id'] = $spuobj->id;
            
            $az = array(
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z'
            );
            $aznum = 0;
            foreach ($data['modelSet'] as $model) {
                //拼接model号
                $data['model'] = $spuobj->spu . $az[$aznum];
                $data['examine_status'] = 'pending';
                $product = $this->create($data);
                if(array_key_exists('carriage_limit_arr', $data)){
                    foreach($data['carriage_limit_arr'] as $logistics_limit_id){
                        $arr['logistics_limits_id'] = $logistics_limit_id;
                        $product->logisticsLimit()->attach($arr);
                    }
                }

                if(array_key_exists('package_limit_arr', $data)){
                    foreach($data['package_limit_arr'] as $wrap_limits_id){
                        $brr['wrap_limits_id'] = $wrap_limits_id;
                        $product->wrapLimit()->attach($brr);
                    }
                }
                
                //获得productID,插入产品图片
                $data['product_id'] = $product->id;
                //echo '<pre>';
                //print_r($data);exit;
                /*$channels = ChannelModel::all();
                foreach ($channels as $channel) {
                    $data['channel_id'] = $channel->id;
                    $product->productMultiOption()->create($data);
                }*/

                //插入产品variation属性
                if (array_key_exists('variations', $model)) {
                    foreach ($model['variations'] as $variation => $variationValues) {
                        //获得此产品的品类所对应的variation属性
                        $variationModel = $catalog->variations()->where('name', '=', $variation)->get()->first();
                        foreach ($variationValues as $value_id => $variationValue) {
                            //获得variation属性对应的属性值
                            $variationValueModel = $variationModel->values()->find($value_id);
                            //多对多插入的attach数组
                            $variation_value_arr = [
                                $variationValueModel->id => [
                                    'variation_value' => $variationValueModel->name,
                                    'variation_id' => $variationModel->id
                                ]
                            ];
                            $product->variationValues()->attach($variation_value_arr);
                        }
                    }
                }

                //插入feature属性
                $keyset = ['featureradio', 'featurecheckbox', 'featureinput'];
                foreach ($keyset as $key) {
                    if (array_key_exists($key, $data)) {
                        foreach ($data[$key] as $feature_id => $feature_value) {
                            if ($key != 'featureinput') {//单选和多选框插入
                                foreach ($feature_value as $value) {
                                    $featureModel = $catalog->features()->find($feature_id);
                                    //找到featureValue对应的ID
                                    $featureValueModel = $featureModel->values()->where('name',
                                        $value)->get()->first()->toArray();
                                    //多对多插入的attach数组
                                    $feature_value_arr = [
                                        $featureValueModel['id'] => [
                                            'feature_value' => $value,
                                            'feature_id' => $feature_id
                                        ]
                                    ];
                                    $product->featureValues()->attach($feature_value_arr);
                                }
                            } else {//input框插入
                                $feature_value_arr = [
                                    $value_id[0]['id'] => [
                                        'feature_value' => $feature_value,
                                        'feature_id' => $feature_id,
                                        'feature_value_id' => 0
                                    ]
                                ];
                                $product->featureValues()->attach($feature_value_arr);
                            }
                        }
                    }
                }
                $product->createItem();
                $aznum++;
            }
            $require_status['status']=3;
            RequireModel::find($data['require_id'])->update($require_status);
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    /**
     * 更新product
     * 2016-1-13 17:48:26 YJ
     * @param $id int, $data array, $files obj
     */
    public function updateProduct($data, $files = null)
    {
        $spu_id = $this->spu_id;
        DB::beginTransaction();
        
        if(array_key_exists('package_limit_arr', $data)){
            foreach($data['package_limit_arr'] as $wrap_limits_id){
                $arr[] = $wrap_limits_id;         
            }
            $this->wrapLimit()->sync($arr);
        }
        if(array_key_exists('carriage_limit_arr', $data)){
            foreach($data['carriage_limit_arr'] as $logistics_limits_id){
                $brr[] = $logistics_limits_id;         
            }
            $this->logisticsLimit()->sync($brr);
        }
        try {
            //更新产品variation属性
            if (array_key_exists('variations', $data)) {
                $ProductVariationValueModel = new ProductVariationValueModel();
                //先删除对应的variation属性,再添加
                $variations = $ProductVariationValueModel->where('product_id', $this->id)->forceDelete();
                foreach ($data['variations'] as $variation_id => $variation_values) {
                    foreach ($variation_values as $variation_value_id => $variation_value) {
                        $variation_value_arr = [
                            $variation_value_id => [
                                'variation_value' => $variation_value,
                                'variation_id' => $variation_id
                            ]
                        ];
                        $this->variationValues()->attach($variation_value_arr);
                    }
                }
            }
            //更新产品feature属性
            if (array_key_exists('features', $data)) {
                $ProductFeatureValueModel = new ProductFeatureValueModel();
                $ProductFeatureValueModel->where('product_id', $this->id)->forceDelete();
                foreach ($data['features'] as $feature_id => $feature_values) {
                    if (is_array($feature_values)) {//feature为多选框
                        foreach ($feature_values as $feature_value) {
                            $featureModel = $this->catalog->features()->find($feature_id);
                            //找到featureValue对应的ID
                            $featureValueModel = $featureModel->values()->where('name',
                                $feature_value)->get()->first()->toArray();
                            $feature_value_arr = [
                                $featureValueModel['id'] => [
                                    'feature_value' => $feature_value,
                                    'feature_id' => $feature_id
                                ]
                            ];
                            $this->featureValues()->attach($feature_value_arr);
                        }
                    } else {//feature为单选框
                        $feature_value_arr = [
                            $featureValueModel['id'] => [
                                'feature_value' => $feature_values,
                                'feature_id' => $feature_id
                            ]
                        ];
                        $this->featureValues()->attach($feature_value_arr);
                    }

                }
                //feature为input框
                foreach ($data['featureinput'] as $featureInputKey => $featureInputValue) {
                    $feature_value_arr = [
                        $featureValueModel['id'] => [
                            'feature_value' => $featureInputValue,
                            'feature_id' => $featureInputKey,
                            'feature_value_id' => 0
                        ]
                    ];
                    $this->featureValues()->attach($feature_value_arr);
                }
            }
            //更新图片
            $data['product_id'] = $this->id;
            $data['spu_id'] = $spu_id;
            $data['type'] = 'original';
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
            $imageModel = new ImageModel();
            foreach ($files as $key => $file) {
                if ($file != '') {
                    if (substr($key, 0, 5) == 'image') {
                        $image_id = $imageModel->singleCreate($data, $file, $key);
                    } else {
                        $product_image_id = substr($key, 14);
                        $imageModel->destroy($product_image_id);
                        $image_id = $imageModel->singleCreate($data, $file, $key);
                        if ($this->default_image == $product_image_id) {
                            $data['default_image'] = $image_id;
                        }
                    }

                }
            }
            //更新基础信息
            $this->update($data);
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
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
     * 创建item
     * 2016-1-13 17:48:26 YJ
     * @param array product_id_array 产品id字符串
     * @return array
     */
    public function createItem()
    {
        //获得variation属性集合
        $variations = $this->variationValues->toArray();
        //产品model号赋值
        $model = $this->model;

        foreach ($variations as $key => $value) {
            $item = $model . ($key + 1);
            $product_data = $this->toArray();
            $product_data['sku'] = $item;
            $product_data['product_id'] = $this->id;
            $product_data['status'] = 'sellWaiting';
            unset($product_data['id']);
            //echo '<pre>';print_r($product_data);exit;
            $this->item()->create($product_data);
        }

        $this->status = "selling";
        $this->save();
    }

    public function destoryProduct()
    {
        //删除product对应的item
        foreach ($this->item as $item) {
            $item->delete();
        }
        $this->variationValues()->detach();
        $this->featureValues()->detach();
        //删除product
        $this->delete();
    }

    public function updateEditProduct($model, $data)
    {
        $model->update($data);
    }

    /**
     * 编辑渠道产品图片资料
     * 2016-3-11 14:00:41 YJ
     * @param array $data ,$files 图片
     */
    public function updateProductImage($data, $files = null)
    {
        DB::beginTransaction();
        $imageModel = new ImageModel();
        foreach ($files as $key => $file) {
            if ($file != '') {
                if (substr($key, 0, 5) == 'image') {
                    $image_id = $imageModel->singleCreate($data, $file, $key);
                } else {
                    $type_array = explode('_', $key);
                    $type = $type_array[0];
                    switch ($type) {
                        case 'ebay':
                            $data['type'] = 'ebay';
                            break;

                        case 'amazon':
                            $data['type'] = 'amazon';
                            break;

                        case 'aliexpress':
                            $data['type'] = 'aliexpress';
                            break;

                        case 'original':
                            $data['type'] = 'original';
                            break;
                    }
                    $product_image_id = $type_array[2];
                    $imageModel->destroy($product_image_id);
                    $image_id = $imageModel->singleCreate($data, $file, $key);
                }

            }
        }

        $data['edit_status'] = $data['edit_status'];
        $this->update($data);

        DB::commit();
    }

    /**
     * 渠道产品审核
     * 2016-3-11 14:00:41 YJ
     * @param int $status 审核状态
     */
    public function examineProduct($status)
    {
        $data['edit_status'] = $status;
        $this->update($data);
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
            $pre = $language['language'];
            foreach ($language as $prefix => $value) {
                $arr[$pre."_".$prefix] = $value;
            }
            //print_r($arr);
            //exit;
            $model = $this->productMultiOption->where("channel_id", (int)$channel_id)->first();
            if($model){
                $model->update($arr);
            }
           
        }
        //
    }

    public function tagRevert($data){
        foreach($data as $key=>$_data){
            switch ($_data) {
                case 'photo':
                    $data[$key] = 1;
                    break;
                
                case 'link':
                    $data[$key] = 2;
                    break;

                case 'logo':
                    $data[$key] = 6;
                    break;

                case 'size':
                    $data[$key] = 8;
                    break;

                case 'color':
                    $data[$key] = 7;
                    break;

                case 'shape':
                    $data[$key] = 4;
                    break;

                case 'front':
                    $data[$key] = 5;
                    break;

                case 'normal':
                    $data[$key] = 6;
                    break;
            }
        }
        return $data;
    }  

    public function oneSku(){
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        //$imageModel = new ImageModel();
        $model = $this->all();
        foreach($model as $_item){
            $sku = $_item->item[0]->sku;
            $url = 'http://erp.moonarstore.com/getSkuImageInfo/getSkuImageInfo.php?distinct=true&include_sub=true&sku='.$sku;
            //$url = 'http://erp.moonarstore.com/getSkuImageInfo/getSkuImageInfo.php?distinct=true&include_sub=true&sku=HW3184';
            $contents = json_decode(file_get_contents($url));
            if(count($contents)){
                foreach ($contents as $image) {
                    $data['spu_id'] = $_item->item[0]->product->spu_id;
                    $data['product_id'] = $_item->item[0]->product_id;
                    $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' ;
                    $data['name'] = $image->filename;
                    $arr = (array)$image->fileId;
                    $image_url = 'http://erp.moonarstore.com/getSkuImageInfo/getSkuImage.php?id='.$arr['$id'];
                    $disk = Storage::disk('product');
                    Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($image_url));
                    $imageModel = ImageModel::create($data);
                    $tags = $image->tags;
                    $tags = $this->tagRevert($tags);
                    $imageModel->labels()->attach($tags);
                }
            }
        }
    }
}
