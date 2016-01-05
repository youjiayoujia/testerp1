<?php

namespace App\Models;

use App\Base\BaseModel;

class Catalog extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catalogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function sets()
    {
        return $this->hasMany('App\Models\Set');
    }
	
	public function product_image()
    {
        return $this->belongsTo('App\Models\Product_imageModel');
    }

}
