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

    public function exportAll()
    {
        $all = $this->all();
        $rows = '';
        foreach($all as $model) {
            $rows[] = [
                'id' => $model->id,
                'channel_id' => $model->channel_id,
                'ordernum' => $model->ordernum,
                'name' => $model->name,
                'email' => $model->email,
                'zipcode' => $model->zipcode,
                'type' => $model->type,
                'remark' => $model->remark,
                'total_order' => $model->total_order,
                'refund_order' => $model->refund_order,
                'refund_rate' => $model->refund_rate,
            ];
        }
        return $rows;
    }

    public function exportPart($blacklist_id_arr)
    {
        $part = $this->whereIn('id', $blacklist_id_arr)->get();
        $rows = '';
        foreach($part as $model) {
            $rows[] = [
                'id' => $model->id,
                'channel_id' => $model->channel_id,
                'ordernum' => $model->ordernum,
                'name' => $model->name,
                'email' => $model->email,
                'zipcode' => $model->zipcode,
                'type' => $model->type,
                'remark' => $model->remark,
                'total_order' => $model->total_order,
                'refund_order' => $model->refund_order,
                'refund_rate' => $model->refund_rate,
            ];
        }
        return $rows;
    }

}