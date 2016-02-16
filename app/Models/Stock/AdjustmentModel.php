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
    protected $fillable = ['adjust_form_id', 'warehouses_id', 'adjust_by', 'adjust_time', 'remark', 'status', 'check_by', 'check_time', 'created_at'];

    // 用于查询
    public $searchFields = ['adjust_form_id'];


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
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function adjustment()
    {
        return $this->hasMany('App\Models\Stock\AdjustFormModel', 'stock_adjustments_id', 'id');
    }
}
