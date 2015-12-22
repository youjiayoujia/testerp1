<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:13
 */

namespace App\Models;


use App\Base\BaseModel;

class LogisticsShippingModel extends BaseModel
{
    protected $table = 'logistics_shipping';

    protected $fillable = ['short_code', 'logistics_type', 'species', 'warehouse', 'logistics_id', 'type_id', 'url', 'api_docking', 'is_enable'];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

    public function logisticsType()
    {
        return $this->belongsTo('App\Models\LogisticsTypeModel', 'type_id', 'id');
    }
}