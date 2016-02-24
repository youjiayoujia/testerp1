<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Product\SupplierModel;

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
        'update' => []
    ];

	protected $fillable = [
        'product_id','sku','weight','inventory','name','c_name','alias_name','alias_cname','catalog_id','supplier_id','supplier_sku','second_supplier_id','supplier_info','purchase_url'
        ,'purchase_price','purchase_carriage','product_size','package_size','carriage_limit','carriage_limit_1','package_limit','package_limit_1','status','remark'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
}