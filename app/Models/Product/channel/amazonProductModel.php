<?php

namespace App\Models\product;

use App\Base\BaseModel;

class amazonProductModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amazonProduct';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id'];
}
