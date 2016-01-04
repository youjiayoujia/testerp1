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
    protected $fillable = ['adjust_form_id', 'item_id', 'sku', 'type', 'warehouses_id', 'warehouse_positions_id', 'amount', 'total_amount', 'remark', 'adjust_man_id', 'adjust_time', 'status', 'check_man_id', 'check_time', 'created_at'];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }
}
