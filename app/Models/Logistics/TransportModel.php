<?php
/**
 * 运输方式模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/30
 * Time: 下午2:40
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class TransportModel extends BaseModel
{
    protected $table = 'logistics_transports';

    public $searchFields = ['name' => '名称'];

    protected $fillable = [
        'name',
        'code',
    ];

    public $rules = [
        'create' => [
            'name' => 'required',
            'code' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'code' => 'required',
        ],
    ];
}