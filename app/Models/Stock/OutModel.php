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
    protected $table = 'stockouts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'amount', 'total_amount', 'remark', 'warehouses_id', 'warehouse_positions_id', 'typeof_stockout', 'typeof_stockout_id'];
}
