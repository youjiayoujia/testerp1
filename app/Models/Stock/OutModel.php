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
    public $searchFields = [''];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required|max:128',
            'quantity' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'amount' => 'required|integer',
        ],
        'update' => [
            'sku' => 'required|max:128',
            'quantity' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'amount' => 'required|integer',
        ]
    ];
    
    /**
     *  get the relationship between the two module
     *
     *  @return connection
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
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
            if($this->stockAdjustment)
                return $this->stockAdjustment->adjust_form_id;
            else
                return '';
        if($this->type == 'ALLOTMENT')
            if($this->stockAllotment)
                return $this->stockAllotment->allotment_id;
            else 
                return '';
    }

    /**
     *  get the relationship between the two module 
     *
     *  @return connection
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
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
