<?php

namespace App\Models;

use App\Base\BaseModel;

class CommandModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    //查询
    public $searchFields = ['signature'];

}
