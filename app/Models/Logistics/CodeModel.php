<?php
/**
 * 跟踪号模型
 *
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

    protected $fillable = [
        'logistics_id',
        'code',
        'package_id',
        'status',
        'used_at'
    ];

    protected $searchFields = ['logistics_id', 'code', 'status', 'package_id'];

    public $rules = [
        'create' => [
            'logistics_id' => 'required',
            'code' => 'required',
            'status' => 'required',
        ],
        'update' => [
            'logistics_id' => 'required',
            'code' => 'required',
            'status' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

}