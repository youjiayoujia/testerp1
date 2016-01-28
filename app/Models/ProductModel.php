<?php

namespace App\Models;

use App\Base\BaseModel;

class ProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
}
