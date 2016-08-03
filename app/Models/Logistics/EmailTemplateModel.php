<?php
/**
 * 回邮模版模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午3:54
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class EmailTemplateModel extends BaseModel
{
    protected $table = 'logistics_email_templates';

    public $searchFields = [
        'customer' => '协议客户',
        'zipcode' => '邮编',
        'phone' => '电话',
        'unit' => '退件单位',
        'sender' => '寄件人'
    ];

    protected $fillable = [
        'customer',
        'address',
        'zipcode',
        'phone',
        'unit',
        'sender',
        'remark',
        'country_code',
        'province',
        'city',

    ];

    public $rules = [
        'create' => [
            'customer' => 'required|unique:logistics_email_templates,customer',
            'address' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
            'unit' => 'required',
            'sender' => 'required',
            'country_code' => 'required',
            'province' => 'required',
            'city' => 'required',
        ],
        'update' => [
            'customer' => 'required|unique:logistics_email_templates,customer,{id}',
            'address' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
            'unit' => 'required',
            'sender' => 'required',
            'country_code' => 'required',
            'province' => 'required',
            'city' => 'required',
        ],
    ];

}