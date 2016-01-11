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
        'country_id'
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountryModel', 'country_id', 'id');
    }

}