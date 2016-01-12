<?php
/**
 * 物流分区报价(小包)模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 上午11:42
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ZonePricePacketModel extends BaseModel
{
    protected $table = 'logistics_zone_packet_prices';

    protected $fillable = [
        'name',
        'shipping',
        'price',
        'other_price',
        'discount',
    ];

}