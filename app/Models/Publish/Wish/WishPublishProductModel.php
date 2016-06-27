<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-15
 * Time: 14:46
 */
namespace App\Models\Publish\Wish;

use App\Base\BaseModel;

class WishPublishProductModel extends BaseModel
{

    protected $table = 'wish_publish_product';

    protected $fillable = [ 'account_id',
                            'productID',
        'publishedTime',
        'status',
        'is_promoted',
        'review_status',
        'sellerID',
        'product_description',
        'product_name',
        'parent_sku',
        'tags',
        'product_type_status',
        'brand',
        'landing_page_url',
        'upc',
        'extra_images',
        'number_saves',
        'number_sold'
    ];

    protected $searchFields = [];

    protected $rules = [];

    public function details()
    {
        return $this->hasMany('App\Models\Publish\Wish\WishPublishProductDetailModel', 'product_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'sellerID', 'id');
    }

}