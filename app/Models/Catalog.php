<?php

namespace App\Models;

use App\Base\BaseModel;

class Catalog extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'catalogs';
    protected $fillable = ['name'];

    public function sets()
    {
        return $this->hasMany('App\Models\Catalog\SetModel','catalog_id');
    }

    public function attributes()
    {
        return $this->hasMany('App\Models\Catalog\AttributeModel','catalog_id');
    }

    public function features()
    {
        return $this->hasMany('App\Models\Catalog\FeatureModel','catalog_id');
    }

}
