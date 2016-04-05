<?php

namespace App\Models;

use App\Base\BaseModel;

class WarehouseModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'province', 'city', 'address', 'contact_by', 'telephone', 'type', 'volumn', 'is_available', 'is_default'];

    // 规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouses,name',
            'type' => 'required',
            'volumn' => 'required|digits_between:1,10'
        ],
        'update' => [
            'name' => 'required|max:128|unique:warehouses,name,{id}',
            'type' => 'required',
            'volumn' => 'required|digits_between:1,10'
        ]
    ];

    //查询
    public $searchFields=['name'];

    /**
     * get the relationship
     * 
     *  @return
     *
     */
    public function position()
    {
        return $this->hasMany('App\Models\Warehouse\PositionModel', 'warehouse_id' ,'id');
    }

    /**
     * get the relationship
     * 
     *  @return
     *
     */
    public function contactByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'contact_by', 'id');
    }
}
