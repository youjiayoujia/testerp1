<?php
/**
 * 面单模版模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/13
 * Time: 下午2:42
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class TemplateModel extends BaseModel
{
    protected $table = 'logistics_templates';

    public $searchFields = ['name' => '面单名称', 'view' => '视图'];

    protected $fillable = [
        'name',
        'view'
    ];

    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_templates,name',
            'view' => 'required'
        ],
        'update' => [
            'name' => 'required|unique:logistics_templates,name,{id}',
            'view' => 'required'
        ],
    ];

}