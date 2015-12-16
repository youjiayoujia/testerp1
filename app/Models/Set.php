<?php

namespace App\Models;

use App\Base\BaseModel;

class Set extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['catalog_id', 'name'];

    public function catalog()
    {
        return $this->belongsTo('App\Models\Catalog');
    }

    public function values()
    {
        return $this->hasMany('App\Models\SetValue');
    }

}
