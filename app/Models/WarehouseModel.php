<?php

namespace App\Models;

use App\Base\BaseModel;

class WarehouseModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'province', 'city', 'type', 'volumn', 'is_available', 'is_default'];

}
