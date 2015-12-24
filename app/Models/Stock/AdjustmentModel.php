<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AdjustmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'type', 'warehouses_id', 'warehouse_positions_id', 'amount', 'total_amount', 'adjust_man_id', 'adjust_time', 'status', 'check_man_id', 'check_time', 'created_at'];
}
