<?php

namespace App\Models;

use App\Base\BaseModel;

class Product extends BaseModel
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
    protected $fillable = ['name', 'c_name'];

    public function brand()
    {
        return $this->belongsTo('App\Models\BrandModel');
    }

    public function getList()
    {
        return $this->paginate(15);
    }
}
