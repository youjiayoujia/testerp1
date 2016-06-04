<?php
/**
 * 渠道账号模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Channel;

use App\Base\BaseModel;

class AccountModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account',
        'alias',
        'channel_id',
        'country_id',
        'domain',
        'sync_cycle',
        'is_available',
        'operator_id',
        'customer_service_id',
        'service_email',
        'is_merge_package',
        'is_thanks',
        'is_picking_list',
        'is_rand_sku',
        'image_domain',
        'is_clearance',
        'order_prefix',
        'amazon_api_url',
        'amazon_marketplace_id',
        'amazon_seller_id',
        'amazon_accesskey_id',
        'amazon_accesskey_secret',
        'aliexpress_member_id',
        'aliexpress_appkey',
        'aliexpress_appsecret',
        'aliexpress_returnurl',
        'aliexpress_refresh_token',
        'aliexpress_access_token',
        'aliexpress_access_token_date',
        'wish_publish_code',
        'wish_client_id',
        'wish_client_secret',
        'wish_redirect_uri',
        'wish_refresh_token',
        'wish_access_token',
        'wish_expiry_time',
        'wish_proxy_address',
        'wish_sku_resolve',
        'lazada_access_key',
        'lazada_user_id',
        'lazada_site',
        'lazada_currency_type',
        'lazada_currency_type_cn',
        'lazada_api_host',
        'ebay_developer_account',
        'ebay_developer_devid',
        'ebay_developer_appid',
        'ebay_developer_certid',
        'ebay_token',
        'ebay_eub_developer',

        'cd_currency_type',
        'cd_currency_type_cn',
        'cd_account',
        'cd_token_id',
        'cd_pw',
        'cd_sales_account',


    ];

    public $searchFields = ['account', 'alias'];

    protected $rules = [
        'create' => [
            'account' => 'required',
            'alias' => 'required',
            'channel_id' => 'required',
            'operator_id' => 'required',
            'customer_service_id' => 'required',
        ],
        'update' => [
            'account' => 'required',
            'alias' => 'required',
            'channel_id' => 'required',
            'operator_id' => 'required',
            'customer_service_id' => 'required',
        ]
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'country_id', 'id');
    }


    public function operators()
    {
        return $this->belongsToMany('App\Models\UserModel', 'channel_account_operators',
            'channel_account_id', 'user_id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'operator_id', 'id');
    }

    public function customer_service()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');

    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderModel', 'channel_account_id');
    }

    public function paypal()
    {
        return $this->belongsToMany('App\Models\PaypalsModel', 'channel_account_paypal',
            'channel_account_id', 'paypal_id');
    }

    public function getMergePackageAttribute()
    {
        return $this->is_merge_package ? '是' : '否';
    }

    public function getThanksAttribute()
    {
        return $this->is_thanks ? '是' : '否';
    }

    public function getPickingListAttribute()
    {
        return $this->is_picking_list ? '是' : '否';
    }

    public function getRandSkuAttribute()
    {
        return $this->is_rand_sku ? '是' : '否';
    }

    public function getClearanceAttribute()
    {
        return $this->is_clearance ? '是' : '否';
    }

    public function getAvailableAttribute()
    {
        return $this->is_available ? '是' : '否';
    }

    public function getApiStatusAttribute()
    {
        $status = [];
        switch ($this->channel->driver) {
            case 'amazon':
                $status = ['Unshipped', 'PartiallyShipped'];
                break;
            case 'aliexpress':
                $status = ['WAIT_SELLER_SEND_GOODS'];
                break;
            case 'lazada':
                $status = ['pending'];
                break;
            case 'wish':
                $status = [];
                break;
            case 'ebay':
                $status=['All'];
                break;
            case 'cdiscount':
                $status=['WaitingForShipmentAcceptation'];
                break;
        }
        return $status;
    }

    public function getApiConfigAttribute()
    {
        $config = [];
        switch ($this->channel->driver) {
            case 'amazon':
                $config = [
                    'serviceUrl' => $this->amazon_api_url,
                    'MarketplaceId.Id.1' => $this->amazon_marketplace_id,
                    'SellerId' => $this->amazon_seller_id,
                    'AWSAccessKeyId' => $this->amazon_accesskey_id,
                    'AWS_SECRET_ACCESS_KEY' => $this->amazon_accesskey_secret,
                ];
                break;
            case 'aliexpress':
                $config = [
                    'appkey' => $this->aliexpress_appkey,
                    'appsecret' => $this->aliexpress_appsecret,
                    'returnurl' => $this->aliexpress_returnurl,
                    'access_token_date' => $this->aliexpress_access_token_date,
                    'refresh_token' => $this->aliexpress_refresh_token,
                    'access_token' => $this->aliexpress_access_token,
                    'aliexpress_member_id' => $this->aliexpress_member_id,
                ];
                break;
            case 'lazada':
                $config = [
                    'lazada_account' => $this->lazada_account,
                    'lazada_access_key' => $this->lazada_access_key,
                    'lazada_user_id' => $this->lazada_user_id,
                    'lazada_site' => $this->lazada_site,
                    'lazada_currency_type' => $this->lazada_currency_type,
                    'lazada_currency_type_cn' => $this->lazada_currency_type_cn,
                    'lazada_api_host' => $this->lazada_api_host,
                ];
                break;
            case 'wish':
                $config = [
                    'publish_code' => $this->wish_publish_code,
                    'client_id' => $this->wish_client_id,
                    'client_secret' => $this->wish_client_secret,
                    'redirect_uri' => $this->wish_redirect_uri,
                    'refresh_token' => $this->wish_refresh_token,
                    'access_token' => $this->wish_access_token,
                    'expiry_time' => $this->wish_expiry_time,
                    'proxy_address' => $this->wish_proxy_address,
                    'sku_resolve' => $this->wish_sku_resolve,
                ];
                break;
            case 'ebay':
                $config=[
                    'requestToken'=>$this->ebay_token,
                    'devID'=>$this->ebay_developer_devid,
                    'appID'=>$this->ebay_developer_appid,
                    'certID'=>$this->ebay_developer_certid,
                ];
                break;
            case 'cdiscount':
                $config = [
                    'cd_sales_account' => $this->cd_sales_account,
                    'cd_pw' => $this->cd_pw,
                    'cd_token_id' => $this->cd_token_id,
                    'cd_account' => $this->cd_account,
                    'cd_currency_type_cn' => $this->cd_currency_type_cn,
                    'cd_currency_type' => $this->cd_currency_type,
                    'cd_expires_in' => $this->cd_expires_in,
                ];
                break;
        }
        return $config;
    }

    public function createAccount()
    {
        $channel = $this->create(request()->all());
        $operatorIds = explode(',', request()->input("operator_ids"));
        $channel->operators()->attach($operatorIds);
        return $channel;
    }

    public function updateAccount()
    {
        $this->update(request()->all());
        $operatorIds = explode(',', request()->input("operator_ids"));
        $this->operators()->sync($operatorIds);
        return $this;
    }

    public function destoryAccount()
    {
        $this->operators()->detach();
        $this->delete();
    }

}
