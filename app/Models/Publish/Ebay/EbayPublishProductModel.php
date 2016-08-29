<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-08-19
 * Time: 13:31
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
class EbayPublishProductModel extends BaseModel
{

    protected $table = 'ebay_publish_product';

    protected $fillable = [
        'account_id',
        'item_id',
        'primary_category',
        'secondary_category',
        'title',
        'sub_title',
        'sku',
        'site_name',
        'site',
        'start_price',
        'quantity',
        'reserve_price',
        'buy_it_now_price',
        'listing_type',
        'view_item_url',
        'listing_duration',
        'dispatch_time_max',
        'private_listing',
        'payment_methods',
        'paypal_email_address',
        'currency',
        'location',
        'postal_code',
        'quantity_sold',
        'store_category_id',
        'condition_id',
        'condition_description',
        'picture_details',
        'item_specifics',
        'variation_picture',
        'return_policy',
        'variation_specifics',
        'shipping_details',
        'status',
        'is_out_control',
        'multi_attribute',
        'seller_id',
        'description',
        'start_time',
        'update_time'
    ];

    public $searchFields = ['item_id' => 'Ebay ItemID'];
    protected $rules = [];


    public function details()
    {
        return $this->hasMany('App\Models\Publish\Ebay\EbayPublishProductDetailModel', 'publish_id', 'id');

    }

    public function getEbayOutControlAttribute()
    {
        return $this->is_out_control==1 ? '是' : '否';
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    /** 获取对应渠道账号
     * @param $channel_id 渠道号
     * @return array
     */
    public function getChannelAccount($channel_id){
        $return=[];
        $result =  AccountModel::where(['channel_id'=>$channel_id,'is_available'=>'1'])->get();
        foreach($result as $account){
            $return[$account->id]=$account->account;
        }
        return $return;
    }
}