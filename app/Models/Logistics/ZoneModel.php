<?php
/**
 * 物流分区模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:22
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ZoneModel extends BaseModel
{
    protected $table = 'logistics_zones';

    protected $fillable = [
        'name',
        'logistics_id',
        'countries'
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

}