<?php
namespace App\Models;

use App\Models\Product\SupplierModel;
use App\Base\BaseModel;

class ItemModel extends BaseModel
{
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

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }

    public function secondSupplierName($id)
    {
        $supplier = new SupplierModel();
        return $supplier::find($id)->name;
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'item_id');
    }

    public function getStock($warehoustPosistionId)
    {
        return $this->stocks()->where('warehouse_position_id', $warehoustPosistionId)->first();
    }

    public function in($warehoustPosistionId, $quantity, $amount)
    {
        $stock = $this->getStock($warehoustPosistionId);
        if ($stock) {
            $stock->in($quantity, $amount);
        } else {
            $stock->createNew($item_id, $warehoustPosistionId, $quantity, $amount);
        }
    }

    public function hold($warehoustPosistionId, $quantity)
    {
        $stock = $this->getStock($warehoustPosistionId);
        $stock->hold($quantity);
    }

    public function unhold($warehoustPosistionId, $quantity)
    {
        $stock = $this->getStock($warehoustPosistionId);
        $stock->unhold($quantity);
    }

    public function out($warehoustPosistionId, $quantity)
    {
        $stock = $this->getStock($warehoustPosistionId);
        $stock->out($quantity);
    }
}
