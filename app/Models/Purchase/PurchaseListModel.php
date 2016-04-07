<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\ProductAbnormalModel;

class PurchaseListModel extends BaseModel
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
	 
    protected $fillable = ['type','status','order_id','sku_id','supplier_id','stock','purchase_num','arrival_num','lack_num','platform_id','user_id','update_userid','warehouse_id','purchase_order_id','postage','cost','purchase_cost','costExamineStatus'];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku','sku');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	 public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseOrderModel', 'purchase_order_id');
    }
	
	
	/**
     * 跟新采购需求
     *
     * @param $id,$data
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
 	public function purchaseListUpdate($id,$data)
	{
		$purchaseItem=$this->find($id);
		if($data['active_status']>0){
			$purchaseItem->active=3;
			$purchaseItem->active_status=$data['active_status'];
			}
		if($purchaseItem->status<2){
		$purchaseItem->costExamineStatus=$data['costExamineStatus'];
		$purchase_num=$purchaseItem->purchase_num;
		$purchaseItem->arrival_num=$data['arrival_num'];	
		$purchaseItem->lack_num=$purchase_num-$data['arrival_num'];
		if($data['arrival_num']==$purchaseItem->purchase_num){
			$purchaseItem->status=2;
		}
		$purchaseItem->save();
		$num=$this->where('purchase_order_id',$purchaseItem->purchase_order_id)->where('status','<>',2)->count('id');	
		if($num==0){
			$purchaseOrder=new PurchaseOrderModel;
			$puchaseO=$purchaseOrder->find($purchaseItem->purchase_order_id);
			$puchaseO->status=2;
			$puchaseO->save();
			}
		}
			
	}
	
	public function activeUpdate($id,$data)
	{	
		$purchaseItem=$this->find($id);
		$purchaseItem->active_status=$data['status'];
		if(!empty($data['newSupplier'])){
			$item=new ItemModel;
			$itemSupplier=$item->find($purchaseItem->sku_id);
			$itemSupplier->supplier_id=$data['newSupplier'];
			$itemSupplier->save();
			$purchaseItem->supplier_id=$data['newSupplier'];
		}
		$purchaseItem->save();
	}
	
}