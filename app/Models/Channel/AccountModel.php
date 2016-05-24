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
        switch ($this->channel->drive) {
            case 'amazon':
                $status = ['Unshipped', 'PartiallyShipped'];
                break;
            case 'aliexpress':
                $status =['WAIT_SELLER_SEND_GOODS'];
                break;
        }
        return $status;
    }

    public function getApiConfigAttribute()
    {
        $config = [];
        switch ($this->channel->drive) {
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
                $config=[
                    'appkey' => $this->aliexpress_appkey,
                    'appsecret' =>$this->aliexpress_appsecret,
                    'returnurl' =>$this->aliexpress_returnurl,
                    'access_token_date' =>$this->aliexpress_access_token_date,
                    'refresh_token' =>$this->aliexpress_refresh_token,
                    'access_token' =>$this->aliexpress_access_token,
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
