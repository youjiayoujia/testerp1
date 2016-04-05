<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class TakingModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_takings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['taking_id', 'stock_taking_by', 'create_taking_adjustment', 'stock_taking_time', 'adjustment_by', 'adjustment_time', 'check_by', 'create_status', 'check_status', 'check_time', 'created_at'];


    // 用于查询
    public $searchFields = ['taking_id'];

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingForm()
    {
        return $this->hasMany('App\Models\Stock\TakingFormModel', 'stock_taking_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
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

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'stock_taking_by', 'id');
    }
}
