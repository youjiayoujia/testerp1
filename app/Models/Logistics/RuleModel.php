<?php
/**
 * 物流分配规则模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午2:52
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class RuleModel extends BaseModel
{
    protected $table = 'logistics_rules';

    public $searchFields = ['country', 'weight_from', 'weight_to', 'order_amount', 'is_clearance', 'priority'];

    protected $fillable = [
        'country',
        'weight_from',
        'weight_to',
        'order_amount',
        'is_clearance',
        'priority',
    ];

    public $rules = [
        'create' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
        ],
        'update' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
        ],
    ];

}