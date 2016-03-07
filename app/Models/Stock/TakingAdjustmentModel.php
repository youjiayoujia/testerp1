<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class TakingAdjustmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_taking_adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_taking_id', 'quantity', 'amount', 'adjustment_by', 'adjustment_time', 'check_by', 'check_status', 'check_time', 'created_at'];


    // 用于查询
    public $searchFields = [''];

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function taking()
    {
        return $this->belongsTo('App\Models\Stock\TakingModel', 'stock_taking_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function adjustmentByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'adjustment_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function checkByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }
}
