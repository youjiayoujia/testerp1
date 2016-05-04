<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class HoldModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_holds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quantity', 'type', 'remark', 'relation_id', 'stock_id', 'created_at'];


    // 用于查询
    public $searchFields = [''];
    
    /**
     *  make the accessor. 
     *  get the name by key in config.
     *
     *  @return name(by type)
     */
    public function getTypeNameAttribute()
    {
        $buf = config('hold');
        if(array_key_exists($this->type, $buf))
            return $buf[$this->type];
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
        if($this->type == 'MAKE_ACCOUNT')
            return '库存导入';
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
}