<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;

class PurchaseItemModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_items';
    public $rules = [
        'create' => [
            'type' => 'required',
            'purchase_num' => 'required',
            'platform_id' => 'required',
            'warehouse_id' => 'required',
            'userid' => 'required',
			'sku_id' => 'required',
        ],
        'update' => [
 			'status' => 'required',
        ]
    ];
    public $searchFields = ['id', 'supplier_id', 'platform_id','warehouse_id','user_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['type','status','order_id','sku_id','supplier_id','stock','purchase_num','arrival_num','lack_num','platform_id','user_id','update_userid','warehouse_id','purchase_order_id','postage','cost','purchase_cost'];
   public function purchaseItemImage()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
   /* public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
    public function variationValue()
    {
        return $this->hasMany('App\Models\Product\ProductVariationValueModel', 'product_id');
    }
    public function item()
    {
        return $this->hasMany('App\Models\ItemModel', 'product_id');
    }*/
	
	
	public function purchasestore($data)
	{
		$data['lack_num']=$data['purchase_num'];
		$item=new ItemModel();
		$productItem=$item->find($data['sku_id']);
		$data['supplier_id']=$productItem['supplier_id'];
		$data['cost']=$productItem['purchase_price'];
		$this->create($data);
	}
 	public function purchaseItemUpdate($id,$data)
	{
		$purchaseItem=$this->find($id);
		$purchase_num=$purchaseItem->purchase_num;
		$purchaseItem->arrival_num=$data['arrival_num'];	
		$purchaseItem->lack_num=$purchase_num-$data['arrival_num'];
		$purchaseItem->save();	
	}
}