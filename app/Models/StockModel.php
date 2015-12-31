<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\StockModel;

class StockModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'sku', 'warehouses_id', 'warehouse_positions_id', 'all_amount', 'available_amount', 'hold_amount', 'total_amount', 'created_at'];

    /**
     * get the relationship between the two module
     *
     * @return 
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }

    public function getUnitCostAttribute()
    {
        $obj = StockModel::where('sku', $this->sku)->get()->toArray();
        $money = '';
        $amount = '';
        for($i=0; $i < count($obj); $i++)
        {
            $money += $obj[$i]['total_amount'];
            $amount += $obj[$i]['all_amount'];
        }

        return round($money/$amount, 3);
    }
}
