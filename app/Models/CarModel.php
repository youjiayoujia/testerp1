<?php

namespace App\Models;

use App\Base\BaseModel;

class CarModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'color'];

    public function brand()
    {
        return $this->belongsTo('App\Models\BrandModel');
    }

}
