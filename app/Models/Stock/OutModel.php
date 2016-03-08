<?php

namespace App\Models\Stock;

use App\Base\BaseModel;
use App\Models\Stock\AdjustmentModel;

class OutModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_outs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quantity', 'amount', 'type', 'remark', 'relation_id', 'stock_id', 'created_at'];

    // 用于查询
    public $searchFields = ['quantity'];
    
    /**
     *  get the relationship between the two module
     *
     *  @return connection
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    /**
     * accessor get the relation name 
     *
     * @return name
     *
     */
    public function getRelationNameAttribute()
    {
        if($this->type == 'ADJUSTMENT')
            return $this->stockAdjustment ? $this->stockAdjustment->adjust_form_id : '';
        if($this->type == 'ALLOTMENT')
            return $this->stockAllotment ? $this->stockAllotment->allotment_id : '';
        if($this->type == 'INVENTORY_PROFIT' || $this->type == 'SHORTAGE')
            return $this->stockTaking ? $this->stockTaking->taking_id : '';
    }

    /**
     *  get the relationship between the two module 
     *
     *  @return connection
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    /**
     * return the relationship between the two module 
     *
     *  @return
     *
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }

    /**
     *  get the relation between the two Model 
     *
     *  @return none
     *
     */
    public function stockAdjustment()
    {
        return $this->belongsTo('App\Models\Stock\AdjustmentModel', 'relation_id', 'id');
    }

    /**
     * get the relation between the two Model
     * 
     *  @return relation
     *
     */
    public function stockAllotment()
    {
        return $this->belongsTo('App\Models\Stock\AllotmentModel', 'relation_id', 'id');
    }

    /**
     * get the relation between the two Model
     * 
     *  @return relation
     *
     */
    public function stockTaking()
    {
        return $this->belongsTo('App\Models\Stock\TakingModel', 'relation_id', 'id');
    }
    
    /**
     *  make accessor!
     *  get the name by key in config.
     *
     *  @return name(by type)
     *  
     */
    public function getTypeNameAttribute()
    {
        $buf = config('out');
        return $buf[$this->type];
    }
}
