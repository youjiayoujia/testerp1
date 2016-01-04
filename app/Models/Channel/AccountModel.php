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
        'channel_id',
        'account',
        'type',
        'country',
        'currency',
        'prefix',
        'title',
        'brief',
        'token',
        'created_by',
        'updated_by'
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel');
    }

}
