<?php

namespace App\Models\Warehouse;

use App\Base\BaseModel;

class PositionModel extends BaseModel
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
    protected $fillable = ['name', 'warehouses_id', 'remark', 'size', 'is_available'];

    // 用于规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouse_positions,name',
            'warehouses_id' => 'required',
            'size' => 'required'
            ],
        'update' => [
            'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
            'warehouses_id' => 'required',
            'size' => 'required'
            ]
    ];

    //查询
    public $searchFields = ['name'];
    
    public function warehouse()
    {
       return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

}
