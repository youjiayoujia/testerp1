<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class CarryOverModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_carry_overs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['carry_over_time', 'stock_id', 'begin_quantity', 'begin_amount', 'over_quantity', 'over_amount', 'created_at'];


    //查询
    public $searchFields = ['carry_over_time'];
    
    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }
}
