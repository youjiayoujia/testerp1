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
    protected $fillable = ['size'];

    public function brand()
    {
        return $this->belongsTo('App\Models\BrandModel');
    }
	
	public function product_image()
    {
        return $this->belongsTo('App\Models\Product_imageModel');
    }

}
