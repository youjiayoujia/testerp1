<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\CatalogModel;
use App\Models\SpuModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;
use App\Models\Catalog\variationValueModel;
use App\Models\Catalog\FeatureValueModel;
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
            'catalog_id' => 'required',
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
        ]
    ];

    public $searchFields = ['name', 'id', 'c_name', 'model'];

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
        'product_sale_url',
        'purchase_price',
        'purchase_carriage',
        'product_size',
        'package_size',
        'weight',
        'warehouse_id',
        'upload_user',
        'assigner',
        'default_image',
        'carriage_limit',
        'carriage_limit_1',
        'package_limit',
        'package_limit_1',
        'status',
        'remark',
        'spu_id',
        'second_supplier_id',
        'supplier_sku'
    ];


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

    public function variationValue()
    {
        return $this->hasMany('App\Models\Product\ProductVariationValueModel', 'product_id');
    }

    public function item()
    {
        return $this->hasMany('App\Models\ItemModel', 'product_id');
    }

    public function ProductVariationvalue()
    {
        return $this->belongsToMany('App\Models\Catalog\VariationValueModel', 'product_variation_values', 'product_id', 'variation_value_id')->withTimestamps();
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
            //创建spu，,并插入数据
            $spuobj = SpuModel::create(['spu'=>Tool::createSku()]);
            $data['spu_id'] = $spuobj->id;
            //获取catalog对象,将关联catalog的属性插入数据表
            $catalog = CatalogModel::find($data['catalog_id']);
            foreach ($data['modelSet'] as $model) {
                //拼接model号
                $data['model'] = $spuobj->spu . "-" . $model['model'];;
                $product = $this->create($data);
                //获得productID,插入产品图片
                $data['product_id'] = $product->id;
                //默认图片id为0
                $default_image_id = 0;
                $imageModel = new ImageModel();
                $i=0;
                foreach ($model['image'] as $key => $file) {     
                    if ($file != '') {
                        $image_id = $imageModel->singleCreate($data, $file, $key);
                        //获得首图的product_image_id
                        if ($i == 0) {
                            $default_image_id = $image_id;
                        }
                        $i++;
                    }
                }
                //更新产品首图
                $product->update(['default_image' => $default_image_id]);
                //插入产品variation属性
                if (array_key_exists('variations', $model)) {
                    foreach ($model['variations'] as $variation => $variationValues) {
                        //获得此产品的品类所对应的variation属性
                        $variationModel = $catalog->variations()->where('name', '=', $variation)->get()->first();
                        foreach ($variationValues as $value_id=>$variationValue) {
                            //获得variation属性对应的属性值
                            $variationValueModel = $variationModel->values()->find($value_id);
                            //多对多插入的attach数组
                            $variation_value_arr = [$variationValueModel->id=>['variation_value'=>$variationValueModel->name,'variation_id'=>$variationModel->id]];
                            $product->ProductVariationvalue()->attach($variation_value_arr);
                        }
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
                                //$featureModel = new FeatureValueModel();
                                //$value_id = $featureModel->where('name', '=', $value)->where('feature_id', '=',$feature_id)->get()->toArray();
                                $featureModel = $catalog->features()->find($feature_id);
                                //找到featureValue对应的ID
                                $featureValueModel = $featureModel->values()->where('name',$value)->get()->first()->toArray();
                                //多对多插入的attach数组
                                $feature_value_arr = [$featureValueModel['id']=>['feature_value'=>$value,'feature_id'=>$feature_id]];
                                $spuobj->ProductManyToFeaturevalue()->attach($feature_value_arr);               
                            }
                        } else {//input框插入
                            $feature_value_arr = [$value_id[0]['id']=>['feature_value'=>$feature_value,'feature_id'=>$feature_id,'feature_value_id'=>0]];
                            $spuobj->ProductManyToFeaturevalue()->attach($feature_value_arr);
                        }
                    }
                }
            }
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
        try {
            //更新产品variation属性
            if (array_key_exists('variations', $data)) {
                $ProductVariationValueModel = new ProductVariationValueModel();
                //先删除对应的variation属性,再添加
                $variations = $ProductVariationValueModel->where('product_id', $this->id)->delete();
                foreach ($data['variations'] as $variation_id => $variation_values) {
                    foreach ($variation_values as $variation_value_id=>$variation_value) {
                        $variation_value_arr = [$variation_value_id=>['variation_value'=>$variation_value,'variation_id'=>$variation_id]];
                        $this->ProductVariationvalue()->attach($variation_value_arr);
                    }
                }
            }
            //更新产品feature属性
            if (array_key_exists('features', $data)) {
                $ProductFeatureValueModel = new ProductFeatureValueModel();
                $ProductFeatureValueModel->where('spu_id', $spu_id)->delete();
                foreach ($data['features'] as $feature_id => $feature_values) {
                    if (is_array($feature_values)) {//feature为多选框
                        foreach ($feature_values as $feature_value) {
                            //$featureModel = new FeatureValueModel();
                            //$value_id = $featureModel->where('name', '=', $feature_value)->where('feature_id', '=',$feature_id)->get()->toArray();
                            $featureModel = $this->catalog->features()->find($feature_id);
                            //找到featureValue对应的ID
                            $featureValueModel = $featureModel->values()->where('name',$feature_value)->get()->first()->toArray();
                            $feature_value_arr = [$featureValueModel['id']=>['feature_value'=>$feature_value,'feature_id'=>$feature_id]];
                            $this->spu->ProductManyToFeaturevalue()->attach($feature_value_arr);  
                        }
                    } else {//feature为单选框
                        $feature_value_arr = [$featureValueModel['id']=>['feature_value'=>$feature_values,'feature_id'=>$feature_id]];
                        $this->spu->ProductManyToFeaturevalue()->attach($feature_value_arr);
                    }

                }
                //feature为input框
                foreach ($data['featureinput'] as $featureInputKey => $featureInputValue) {
                    $feature_value_arr = [$featureValueModel['id']=>['feature_value'=>$featureInputValue,'feature_id'=>$featureInputKey,'feature_value_id'=>0]];
                    $this->spu->ProductManyToFeaturevalue()->attach($feature_value_arr);
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
                    $image_id = $imageModel->singleCreate($data, $file, $key);
                    if ($key == 'image0') {
                        $default_image_id = $image_id;
                    }
                }
                $data['default_image'] = $default_image_id;
            }

            //更新基础信息
            $this->update($data);
        } catch (Exception $e) {
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
    public function createItem()
    {
        //获得variation属性集合
        $variations = $this->variationValue;
        $brr = [];
        foreach ($variations as $variation) {
            $brr[$variation->variation_id][] = $variation->variation_value;
        }
        //按照指定格式的数组去笛卡尔及创建item
        $brr = array_values($brr);
        $result = Tool::createDikaer($brr);
        //产品model号赋值
        $model = $this->model;
        foreach ($result as $_result) {
            $item = $model;
            //循环拼接创建item
            foreach ($_result as $__result) {
                $item .= "-" . $__result;
            }
            $product_data = $this->toArray();
            $product_data['sku'] = $item;
            $product_data['product_id'] = $this->id;
            //$item = new ItemModel();
            $this->item()->create($product_data);
        }
        $this->status = 1;
        $this->save();
    }

    public function destoryProduct()
    {
        //删除product对应的item
        foreach ($this->item as $item) {
            $item->delete();
        }
        //删除product
        $this->delete();
    }

}
