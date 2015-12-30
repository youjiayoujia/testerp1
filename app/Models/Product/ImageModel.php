<?php

namespace App\Models\product;

use App\Base\BaseModel;

class ImageModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'user_id', 'type','image_path','image_name'];

}
