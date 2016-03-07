<?php

namespace App\Models\Product\channel;

use App\Base\BaseModel;

class amazonProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amazon_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','choies_info'];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }
}
