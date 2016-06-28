<?php
/**
 * 渠道物流名
 *
 * Created by PhpStorm.
 * User: mc
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ChannelNameModel extends BaseModel
{
    protected $table = 'logistics_channel_names';

    protected $fillable = [
        'channel_id', 'logistics_id', 'name'
    ];

    public $searchFields = ['name'];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}