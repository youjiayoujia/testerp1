<?php

namespace App\Models;

use App\Base\BaseModel;

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
    protected $fillable = ['id', 'spu'];



    public function values()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function productFeatureValue()
    {
        return $this->hasMany('App\Models\Product\ProductFeatureValueModel','spu_id');
    }
}