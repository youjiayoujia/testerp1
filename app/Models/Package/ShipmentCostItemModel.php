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
    	'poarent_id',
    	'hang_number',
    	'package_id',
    	'type',
    	'shipped_at',
    	'code',
    	'destination',
        'all_weight',
        'theory_weight',
        'weight_diff',
        'all_cost',
        'theory_cost',
        'channel_id',
    	'created_at'
    ];
}