<?php
/**
 * 物流分区报价(快递)模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 上午11:45
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ZonePriceExpressModel extends BaseModel
{
    protected $table = 'logistics_zone_express_prices';

    protected $fillable = [
        'name',
        'species_id',
        'fixed_weight',
        'fixed_price',
        'continued_weight',
        'continued_price',
        'other_fixed_price',
        'other_scale_price',
        'discount',
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'species_id', 'id');
    }

}