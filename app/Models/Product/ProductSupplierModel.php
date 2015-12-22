<?php

namespace App\Models\product;

use App\Base\BaseModel;

class ProductSupplierModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'province', 'city', 'address', 'type', 'telephone', 'purchase_id', 'level'];

}
