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
    protected $fillable = ['adjust_forms_id', 'item_id', 'sku', 'type', 'warehouse_positions_id', 'amount', 'total_amount', 'created_at'];

    // 用于查询
    protected $searchFields = ['adjust_form_id', 'sku'];

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }
}
