<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

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
    protected $fillable = ['item_id', 'sku', 'amount', 'total_amount', 'remark', 'warehouses_id', 'warehouse_positions_id', 'type', 'relation_id'];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }
}
