<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class CodeModel extends BaseModel
{
    protected $table = 'logistics_codes';

    protected $fillable = ['logistics_id', 'code', 'package_id', 'status', 'used_at'];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

}