<?php
/**
 * 渠道模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models;

use App\Base\BaseModel;

class ChannelModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'drive', 'brief'];

    protected $searchFields = ['name'];

    protected $rules = [
        'create' => ['name' => 'required|unique:channels,name', 'drive' => 'required'],
        'update' => ['name' => 'required|unique:channels,name,{id}', 'drive' => 'required']
    ];

    public function channelAccount()
    {
        return $this->hasMany('App\Models\Channel\AccountModel', 'channel_id', 'id');
    }

}
