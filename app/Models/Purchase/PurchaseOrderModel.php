<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;

class PurchaseOrderModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders';
    public $rules = [
        'create' => [
            /*'type' => 'required',
            'purchase_num' => 'required',
            'platform_id' => 'required',
            'warehouse_id' => 'required',
            'userid' => 'required',
			'sku_id' => 'required',*/
        ],
        'update' => [
 			/*'status' => 'required',*/
        ]
    ];
    public $searchFields = ['id', 'supplier_id', 'platform_id','warehouse_id','user_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['type','status','order_id','sku_id','supplier_id','stock','purchase_num','arrival_num','lack_num','platform_id','user_id','update_userid','warehouse_id','purchase_order_id','postage','cost','purchase_cost'];
	public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
   /*public function purchaseItem()
    {
        return $this->belongsTo('App\Models\PurchaseItemModel', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
    
    public function supplier()
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
	
	public function getSuppliers($warehouse_id)
	{	
		$data=$this->purchaseItemSupplier($warehouse_id);
		return $data;	
	}
	
	public function addPurchaseOrder($data)
	{
		$checkedItem=explode(',',$data['checkedPurchaseItems']);
		if(!empty($data['checkedPurchaseItems'])){
		$dataArray['user_id']=$data['user_id'];
		$dataArray['warehouse_id']=$data['warehouse_id'];
		$dataArray['supplier_id']=$data['supplier_id'];
		$purchaseOrderId=$this->create($dataArray);
		foreach($checkedItem as $key=>$v){
		$purchaseItemModel= new PurchaseItemModel;
		$purchaseItem=$purchaseItemModel->find($v);
		$purchaseItem['purchase_order_id']=$purchaseOrderId->id;
		$purchaseItem->purchase_order_id=$purchaseItem['purchase_order_id'];
		$purchaseItem->save();
		}
		}
	}


	public function updatePurchaseOrderExamine($purchaseOrderIds)
	{
		foreach($purchaseOrderIds as $key=>$v){
			$this->where('id',$v)->update(['status'=>1]);
			$purchaseItem= new PurchaseItemModel;
			$purchaseItem->where('purchase_order_id',$v)->update(['status'=>2]);
		}
	
	}
}