<?php

namespace App\Models\Product\channel;

use App\Models\Product\ImageModel;
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
                            'purchase_price','purchase_carriage','weight','supplier_info','remark','image_remark','status','edit_status'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
        ],
        'update' => [
            'name' => 'required',
            'c_name' => 'required',       
        ]
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }

    public function createAmazonProduct($data)
    {   
        $data['product_id'] = $data['id'];
        $data['status'] = 0;
        
        $this->create($data);
    }

    public function updateAmazonProduct($data)
    {   
        $this->update($data);
    }

    public function updateAmazonProductImage($data,$files = null)
    {   
        $imageModel = new ImageModel();
        $imageModel->imageCreate($data,$files);
        $data['status'] = 2;
        $this->update($data);
    }

    public function examineAmazonProduct($status)
    {   
        $data['status'] = $status;
        $this->update($data);
    }

}
