<?php
/**
 * 物流分类模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午2:04
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class CatalogModel extends BaseModel
{
    protected $table = 'logistics_catalogs';

    public $searchFields = ['name'];

    protected $fillable = [
        'name'
    ];

    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_catalogs,name',
        ],
        'update' => [
            'name' => 'required|unique:logistics_catalogs,name,{id}',
        ],
    ];

}