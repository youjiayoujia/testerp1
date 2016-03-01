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
        'name',
        'alias',
        'channel_id',
        'country_id',
        'domain',
        'sync_cycle',
        'activate',
        'default_businesser_id',
        'default_server_id',
        'email',
        'delivery_warehouse',
        'merge_package',
        'thanks',
        'picking_list',
        'generate_sku',
        'image_site',
        'clearance',
        'tracking_config',
        'order_prefix',
    ];

    public $searchFields = ['name', 'alias', 'merge_package', 'thanks', 'picking_list'];

    protected $rules = [
        'create' => [
            'name' => 'required',
            'alias' => 'required',
            'channel_id' => 'required',
            'country_id' => 'required',
            'default_businesser_id' => 'required',
            'default_server_id' => 'required',
            'email' => 'required|email',
            'delivery_warehouse' => 'required|max:255',
        ],
        'update' => [
            'name' => 'required',
            'alias' => 'required',
            'channel_id' => 'required',
            'country_id' => 'required',
            'default_businesser_id' => 'required',
            'default_server_id' => 'required',
            'email' => 'required|email',
            'delivery_warehouse' => 'required|max:255',
        ]
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountryModel', 'country_id', 'id');
    }


    public function businessers()
    {
        return $this->belongsToMany('App\Models\UserModel', 'channel_account_user', 'channel_account_id', 'user_id')->withTimestamps();
    }

    public function default_businesser()
    {
        return $this->belongsTo('App\Models\UserModel', 'default_businesser_id', 'id');
    }

    public function default_server()
    {
        return $this->belongsTo('App\Models\UserModel', 'default_server_id', 'id');
    }

}
