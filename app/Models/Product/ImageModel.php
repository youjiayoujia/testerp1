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
    protected $fillable = ['spu_id', 'product_id', 'type', 'path', 'name'];

    public function getSrcAttribute()
    {
        return $this->path . $this->name;
    }
}
