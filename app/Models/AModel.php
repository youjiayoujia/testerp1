<?php

namespace App\Models;

use App\Base\BaseModel;

class AModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'a';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function b()
    {
        return $this->hasOne('App\Models\BModel', 'a_id', 'id');
    }

    public function c()
    {
        return $this->belongsToMany('App\Models\CModel', 'b', 'a_id', 'c_id');
    }
}
