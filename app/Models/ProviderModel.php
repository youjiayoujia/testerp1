<?php

namespace App\Models;

use App\Base\BaseModel;

class ProviderModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'detail_address', 'address', 'type', 'telephone', 'purchase_id', 'level'];

}
