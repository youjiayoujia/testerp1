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
    protected $table = 'b';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['a_id, c_id'];

    public function a()
    {
        return $this->belongsTo('App\Models\AModel', 'a_id', 'id');
    }

    public function c()
    {
        return $this->belongsTo('App\Models\CModel', 'c_id', 'id');
    }
}
