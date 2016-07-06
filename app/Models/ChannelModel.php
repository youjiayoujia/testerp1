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
    protected $fillable = [
        'name',
        'driver',
        'brief',
        'created_at',
        'flat_rate',
        'rate',
        'flat_rate_value',
        'rate_value'
    ];

    public $searchFields = ['name'];

    protected $rules = [
        'create' => ['name' => 'required|unique:channels,name', 'driver' => 'required'],
        'update' => ['name' => 'required|unique:channels,name,{id}', 'driver' => 'required']
    ];

    public function accounts()
    {
        return $this->hasMany('App\Models\Channel\AccountModel', 'channel_id', 'id');
    }

}
