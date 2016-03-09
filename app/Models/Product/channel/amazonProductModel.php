<?php

namespace App\Models\Product\channel;

use App\Base\BaseModel;

class amazonProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amazon_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','choies_info','name','c_name','supplier_id','supplier_sku','product_sale_url','purchase_sale_url',
                            'purchase_price','purchase_carriage','weight','supplier_info','remark','image_remark','status'];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }

    public function createAmazonProduct($data)
    {   
        $data['choies_info'] = 'u are stupid';
        $data['product_id'] = $data['id'];
        $data['status'] = 0;
        
        $this->create($data);
    }

    public function updateAmazonProduct($data)
    {   
        //$data['status'] = 1;
        $this->update($data);
    }

    public function updateAmazonProductImage($file)
    {   
        //$data['status'] = 2;
        $this->update($data);
    }
}
