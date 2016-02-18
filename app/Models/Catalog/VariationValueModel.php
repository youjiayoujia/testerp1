<?php

namespace App\Models\Catalog;

use App\Base\BaseModel;

class VariationValueModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'variation_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attribute_id', 'name'];

    public function set()
    {
        return $this->belongsTo('App\Models\Catalog\VariationModel','attribute_id');
    }
}
