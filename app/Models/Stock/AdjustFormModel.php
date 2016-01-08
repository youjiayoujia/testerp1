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
    protected $fillable = ['adjust_form_id', 'warehouses_id', 'adjust_man_id', 'adjust_time', 'remark', 'status', 'check_man_id', 'check_time', 'created_at'];

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
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function adjustment()
    {
        return $this->hasMany('App\Models\Stock\AdjustmentModel', 'adjust_forms_id', 'id');
    }
}
