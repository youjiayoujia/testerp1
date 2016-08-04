<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\ProductModel;

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

    /*public function productFeatureValue()
    {
        return $this->hasMany('App\Models\Product\ProductFeatureValueModel','spu_id');
    }

    public function ProductManyToFeaturevalues()
    {
        return $this->belongsToMany('App\Models\Catalog\FeatureValueModel', 'product_feature_values', 'spu_id', 'feature_value_id')->withTimestamps();
    }*/
}