<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AllotmentFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'allotment_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_allotments_id', 'warehouse_positions_id', 'item_id', 'sku', 'amount', 'total_amount', 'created_at'];

    /**
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function allotment()
    {
        return $this->hasMany('App\Models\Stock\AllotmentModel', 'stock_allotments_id', 'id');
    }

    /**
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }
}
