<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

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
    protected $fillable = ['item_id', 'sku', 'amount', 'total_amount', 'remark', 'warehouses_id', 'warehouse_positions_id', 'type', 'relation_id'];

    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
        ],
        'update' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'warehouses_id' => 'requiraed|integer',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
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
     *  get the relationship between the two module 
     *
     *  @return connection
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
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
