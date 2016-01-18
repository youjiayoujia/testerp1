<?php
/**
 * 物流方式模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:13
 */

namespace App\Models;

use App\Base\BaseModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logisticses';

    protected $fillable = [
        'short_code',
        'logistics_type',
        'shipping',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'url',
        'api_docking',
        'is_enable'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Models\Logistics\SupplierModel', 'logistics_supplier_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

}