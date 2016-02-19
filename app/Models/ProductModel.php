<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\CatalogModel;
use App\Models\SpuModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
//use App\Models\Product\ProductAttributeValueModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;
//use App\Models\Catalog\AttributeValueModel;
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
            $spumodel = new SpuModel();
            $spu = Tool::createSku();
            $spuarr['spu'] = $spu;
            $spuobj = $spumodel->create($spuarr);
            $data['spu_id'] = $spuobj->id;
            //获取catalog对象,将关联catalog的属性插入数据表
            $catalog = CatalogModel::find($data['catalog_id']);
            foreach ($data['modelSet'] as $model) {
                if (count($model) == 1) {
                    continue;
                }
                $data['model'] = $spu . "-" . $model['model'];;
                $product = $this->create($data);
                //获得productID,插入产品图片
                $data['product_id'] = $product->id;
                //默认图片id为0
                $default_image_id = 0;
                $imageModel = new ImageModel();
                foreach ($model['image'] as $key => $file) {
                    if ($file != '') {
                        $image_id = $imageModel->singleCreate($data, $file, $key);
                        //获得首图的product_image_id
                        if ($key == 'image0') {
                            $default_image_id = $image_id;
                        }
                    }
                }
                //更新产品首图
                $product->update(['default_image' => $default_image_id]);
                //插入产品attribute属性
                if (array_key_exists('variations', $model)) {
                    foreach ($model['variations'] as $variation => $variationValues) {
                        $variationModel = $catalog->variations()->where('name', '=', $variation)->get()->first();
                        foreach ($variationValues as $variationValue) {
                            $variationValueModel = $variationModel->values()->where('name', '=',$variationValue)->get()->first();
                            $variationArray['variation_id'] = $variationModel->id;
                            $variationArray['variation_value'] = $variationValueModel->name;
                            $variationArray['variation_value_id'] = $variationValueModel->id;
                            $variationArray['product_id'] = $product->id;
                            $ProductVariationValueModel = new ProductVariationValueModel();
                            $ProductVariationValueModel->create($variationArray);
                        }
                    }
                }
            }
            //插入feature属性
            $keyset = ['featureradio', 'featurecheckbox', 'featureinput'];
            foreach ($keyset as $key) {
                if (array_key_exists($key, $data)) {
                    foreach ($data[$key] as $feature_id => $feature_value) {
                        $featureArray['feature_id'] = $feature_id;
                        $featureArray['spu_id'] = $spuobj->id;
                        if ($key != 'featureinput') {
                            foreach ($feature_value as $value) {
                                $featureArray['feature_value'] = $value;
                                $productFeatureValueModel = new ProductFeatureValueModel();
                                $featureModel = new FeatureValueModel();
                                $value_id = $featureModel->where('name', '=', $value)->where('feature_id', '=',$feature_id)->get()->toArray();
                                $featureArray['feature_value_id'] = $value_id[0]['id'];
                                $productFeatureValueModel->create($featureArray);
                            }
                        } else {
                            $featureArray['feature_value'] = $feature_value;
                            $featureArray['feature_value_id'] = 0;
                            $productFeatureValueModel = new ProductFeatureValueModel();
                            $productFeatureValueModel->create($featureArray);
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
            //更新产品attribute属性
            if (array_key_exists('variations', $data)) {
                $ProductVariationValueModel = new ProductVariationValueModel();
                $variations = $ProductVariationValueModel->where('product_id', $this->id)->delete();
                foreach ($data['variations'] as $variation_id => $variation_values) {
                    $tmp = [];
                    $tmp['product_id'] = $this->id;
                    $tmp['variation_id'] = $variation_id;
                    $variationValueModel = new variationValueModel();

                    foreach ($variation_values as $variation_value) {
                        $tmp['variation_value'] = $variation_value;
                        $variation_value_id = $variationValueModel->where('name',$variation_value)->where('variation_id', $variation_id)->get()->toArray();
                        $tmp['variation_value_id'] = $variation_value_id[0]['id'];
                        $model = new ProductVariationValueModel();
                        $model->create($tmp);
                    }
                }
            }
            //更新产品feature属性
            if (array_key_exists('features', $data)) {
                $ProductFeatureValueModel = new ProductFeatureValueModel();
                $ProductFeatureValueModel->where('spu_id', $spu_id)->delete();
                foreach ($data['features'] as $feature_id => $feature_values) {
                    $tmp = [];
                    $tmp['spu_id'] = $spu_id;
                    $tmp['feature_id'] = $feature_id;
                    if (is_array($feature_values)) {
                        foreach ($feature_values as $feature_value) {
                            $tmp['feature_value'] = $feature_value;
                            $model = new ProductFeatureValueModel();
                            $featureModel = new FeatureValueModel();
                            $value_id = $featureModel->where('name', '=', $feature_value)->where('feature_id', '=',
                                $feature_id)->get()->toArray();
                            $tmp['feature_value_id'] = $value_id[0]['id'];
                            $model->create($tmp);
                        }
                    } else {
                        $tmp['feature_value'] = $feature_values;
                        $model = new ProductFeatureValueModel();
                        $model->create($tmp);
                    }

                }
                foreach ($data['featureinput'] as $featureInputKey => $featureInputValue) {
                    unset($tmp);
                    $tmp['spu_id'] = $spu_id;
                    $tmp['feature_id'] = $featureInputKey;
                    $tmp['feature_value'] = $featureInputValue;
                    $model = new ProductFeatureValueModel();
                    $model->create($tmp);
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
        $attributes = $this->variationValue;
        
        $brr = [];
        foreach ($attributes as $attribute) {
            $brr[$attribute->attribute_id][] = $attribute->attribute_value;
        }
        $brr = array_values($brr);
        $result = Tool::createDikaer($brr);
        $model = $this->model;
        foreach ($result as $_result) {
            $item = $model;
            foreach ($_result as $__result) {
                $item .= "-" . $__result;
            }
            $product_data = $this->toArray();
            $product_data['sku'] = $item;
            $product_data['product_id'] = $this->id;
            $item = new ItemModel();
            $item->create($product_data);
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
