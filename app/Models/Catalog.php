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
    protected $fillable = ['name'];

    public function sets()
    {
        return $this->hasMany('App\Models\Set');
    }

}
