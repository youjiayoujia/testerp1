<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/17
 * Time: 下午2:23
 */

namespace App\Models;


use App\Base\BaseModel;

class LogisticsTypeModel extends BaseModel
{
    protected $table = 'logistics_type';

    protected $fillable = ['type', 'logistics_id', 'remark'];

    public function logisticsType() {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

}