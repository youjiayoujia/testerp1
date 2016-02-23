<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AdjustFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adjust_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_adjustments_id', 'items_id', 'type', 'warehouse_positions_id', 'quantity', 'amount', 'created_at'];


    //查询
    public $searchFields = ['stock_adjustments_id'];
    
    /**
     * return the relationship between the two Model 
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

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function items()
    {
        return $this->belongsTo('App\Models\ItemModel', 'items_id', 'id');
    }
}
