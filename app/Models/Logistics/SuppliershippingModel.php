<?php
/**
 * 物流商模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:11
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class SuppliershippingModel extends BaseModel
{
    protected $table = 'suppliershipping';

    public $searchFields = ['logistics_type'];

    protected $fillable = [
        'logistics_type', 'supplier_id', 'remark'
    ];

    public $rules = [
        'create' => [
            'logistics_type' => 'required',
            'remark' => 'max:255',
        ],
        'update' => [
            'logistics_type' => 'required',
            'remark' => 'max:255',
        ],
    ];

    // 定义外键关系
    public function supplier()
    {
        return $this->belongsTo('App\Models\Logistics\SupplierModel', 'supplier_id', 'id');
    }

}