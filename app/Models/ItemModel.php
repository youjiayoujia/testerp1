<?php

namespace App\Models;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'items';

	public $searchFields = ['sku'];

    public $rules = [
        'create' => ['sku' => 'required|unique:items,sku'],
        'update' => ['sku' => 'required|unique:items,sku,{id}']
    ];

	protected $fillable = [
        'product_id','sku','weight','inventory','name','c_name','alias_name','alias_cname','catalog_id','supplier_id','supplier_sku','second_supplier_id','supplier_info','purchase_url'
        ,'purchase_price','purchase_carriage','product_size','package_size','carriage_limit','package_limit','status','remark'
    ];
}