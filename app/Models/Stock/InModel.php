<?php

namespace App\Models\Stock;

use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\ItemModel;

class InModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_ins';

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
            'quantity' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'stock_id' => 'required|integer',
            'amount' => 'required|numeric',
        ],
        'update' => [
            'quantity' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'stock_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]
    ];

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
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
            return $this->stockAdjustment->adjust_form_id;
    }
    
    /**
     *  make the accessor. 
     *  get the name by key in config.
     *
     *  @return name(by type)
     */
    public function getTypeNameAttribute()
    {
        $buf = config('in.in');
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
     * 通过sku  获取对应的item_id
     *
     * @param $sku sku值
     * @return ''|id
     *
     */
    public function getItemId($sku)
    {
        $buf = ItemModel::all()->toArray();
        foreach($buf as $item)
            if($item['sku'] == $sku)
                return $item['id'];
        return '';
    }
}
