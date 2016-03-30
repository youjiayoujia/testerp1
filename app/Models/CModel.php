<?php

namespace App\Models;

use App\Base\BaseModel;

class CModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'c';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function b()
    {
        return $this->hasOne('App\Models\BModel', 'c_id', 'id');
    }
}
