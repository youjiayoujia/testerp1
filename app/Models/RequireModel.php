<?php
/**
 * 订单需求模型
 *
 * 2016-04-19
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models;

use App\Base\BaseModel;

class RequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requires';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $searchFields = ['sku'];

    protected $rules = [];

    public function channelAccount()
    {
        return $this->hasMany('App\Models\Channel\AccountModel', 'channel_id', 'id');
    }

}
