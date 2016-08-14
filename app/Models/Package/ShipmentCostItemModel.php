<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ShipmentCostItemModel extends BaseModel
{
    protected $table = 'shipment_cost_items';

    protected $fillable = [
    	'parent_id',
    	'hang_number',
    	'package_id',
    	'type',
    	'shipped_at',
        'logistics_id',
    	'code',
    	'destination',
        'all_weight',
        'theory_weight',
        'all_cost',
        'theory_cost',
        'channel_name',
    	'created_at'
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Package\ShipmentCostModel', 'parent_id', 'id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }
}