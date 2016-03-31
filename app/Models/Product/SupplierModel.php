<?php

namespace App\Models\product;

use App\Base\BaseModel;

class SupplierModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'province', 'city', 'address', 'type', 'telephone', 'purchase_id', 'level'];

    //查询
    public $searchFields = ['name','telephone']; 

    //验证规则
    public $rules = [
            'create' => [   
                    'name' => 'required|max:128|unique:product_suppliers,name',
                    'purchase_id' => 'required|integer',
                    'telephone' => 'required|max:256|digits_between:8,11'
            ],
            'update' => [   
                    'name' => 'required|max:128|unique:product_suppliers,name, {id}',
                    'purchase_id' => 'required|integer',
                    'telephone' => 'required|max:256|digits_between:8,11'
            ]
    ];

    /**
     * return the relation between the two module 
     *
     * @return relation
     */
    public function purchaseName()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_id', 'id');
    }

    /**
     * return the relation between the two module 
     *
     * @return relation
     */
    public function createdByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'created_by', 'id');
    }
}
