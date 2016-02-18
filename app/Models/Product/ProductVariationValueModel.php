<?php
namespace App\Models\Product;
use App\Base\BaseModel;
class ProductVariationValueModel extends BaseModel
{
    protected $table = 'product_variation_values';
    protected $fillable = [
            'product_id','attribute_id','attribute_value','attribute_value_id'
            ];

    public function VariationValue()
    {      
        return $this->belongsTo('App\Models\Catalog\VariationModel','attribute_id');
    }
}