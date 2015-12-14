<?php

namespace App\Models;

use App\Base\BaseModel;

class WarehousePositionModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouse_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'warehouses_id', 'remark', 'size'];

    public function warehouseType(){
       return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

}
