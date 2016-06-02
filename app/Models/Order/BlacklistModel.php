<?php
/**
 * 黑名单模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/5
 * Time: 下午7:44
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class BlacklistModel extends BaseModel
{
    protected $table = 'order_blacklists';

    public $searchFields = ['name', 'email', 'zipcode'];

    protected $fillable = [
        'channel_id',
        'ordernum',
        'name',
        'email',
        'zipcode',
        'type',
        'remark',
        'total_order',
        'refund_order',
        'refund_rate',
    ];

    public $rules = [
        'create' => [
            'ordernum' => 'required',
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'total_order' => 'required',
            'refund_order' => 'required',
            'refund_rate' => 'required',
        ],
        'update' => [
            'ordernum' => 'required',
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'total_order' => 'required',
            'refund_order' => 'required',
            'refund_rate' => 'required',
        ],
    ];

    public function getTypeNameAttribute()
    {
        $arr = config('order.blacklist_type');
        return $arr[$this->type];
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

}