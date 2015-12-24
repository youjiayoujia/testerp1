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
    protected $table = 'stockins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'amount', 'total_amount', 'remark', 'warehouses_id', 'warehouse_positions_id', 'typeof_stockin', 'typeof_stockin_id'];
}
