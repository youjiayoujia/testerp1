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

    public $searchFields = [];


    protected $rules = [];


    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => [
                'productID',
            ],
            'filterSelects' => [
                /* 'status' => config('order.status'),
                 'active' => config('order.active')*/
            ],
            'sectionSelect' => [
                /*'price' => ['amount'],
                'time' => ['created_at']*/
            ],
            'relatedSearchFields' => [
                'details'=>['erp_sku']
                /*'channel' => ['name'],
                'items' => ['sku'],
                'channelAccount' => ['alias'],
                'country' => ['code'],
                'userService' => ['name']*/
            ],
            'selectRelatedSearchs' => [

            ]
        ];
    }

    public function details()
    {
        return $this->hasMany('App\Models\Publish\Wish\WishPublishProductDetailModel', 'product_id', 'id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'sellerID', 'id');
    }

}