<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AllotmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_allotments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['allotment_id', 'out_warehouses_id', 'in_warehouses_id', 'remark', 'allotment_by', 'allotment_time', 'allotment_status', 'check_by', 'check_status', 'check_time', 'checkform_by', 'checkform_time', 'created_at'];

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function outwarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'out_warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function inwarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'in_warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function outposition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }

    /**
     * get the relationship between the two model 
     *
     *  @return
     *
     */
    public function allotmentform()
    {
        return $this->hasMany('App\Models\Stock\AllotmentFormModel', 'stock_allotments_id', 'id');
    }

    /**
     * get the accessAttribute by the allotment_status
     *
     * @return
     */
    public function getStatusNameAttribute()
    {
        $buf = config('in.allotment');
        
        return $buf[$this->allotment_status];
    }

    /**
     * get the allotmentlogistics-$this model relationship
     * 
     * @return
     */
    public function logistics()
    {
        return $this->hasMany('App\Models\Stock\AllotmentLogisticsModel', 'allotments_id', 'id');
    }

}
