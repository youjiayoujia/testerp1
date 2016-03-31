<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;
use App\Models\Purchase\PurchaseOrderModel;

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
            'warehouse_id' => 'required',
            'user_id' => 'required',
			'sku' => 'required',
        ],
        'update' => [
 			'purchase_num' => 'required',
        ]
    ];
    public $searchFields = ['id', 'supplier_id','warehouse_id','user_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['type','status','order_item_id','sku','supplier_id','purchase_num','arrival_num','lack_num','user_id','update_userid','warehouse_id','purchase_order_id','postage','storageStatus','purchase_cost','costExamineStatus','active'];
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
	
	
	/*处理异常状态
	*
	* @param $data
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/

	public function changActive($data){
		$purchaseItem=$this->find($data['id']);
		$purchaseItem->active=$data['active'];
		$purchaseItem->active_status=$data['active_status'];
		$purchaseItem->save();
	}
		
	 
	
	/*上报采购价格
	*
	* @param $data
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	
	public function formSupplierCost($data)
	{
		$purchaseOrder=new PurchaseOrderModel;
		$supplierCost=$this->find($data['id']);
		$cost=$supplierCost->cost;
		if($cost*0.6<$data['supplier_cost'] && $data['supplier_cost']<1.3*$cost){
			$supplierCost->costExamineStatus=2;
		}
		$supplierCost->purchase_cost=$data['supplier_cost']*$supplierCost->purchase_num;
		$supplierCost->save();
		$purchaseOrder->totalCost($supplierCost->purchase_order_id);
	}	
	
	/*回传物流单号和运费
	*
	* @param $data
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	public function fromPostCoding($data){
		$purchaseOrder=new PurchaseOrderModel;
		$fromPostCoding=$this->find($data['purchaseItem_id']);
		$fromPostCoding->post_coding=$data['postCoding'];
		$fromPostCoding->postage=$data['postFee'];
		$fromPostCoding->save();
		$purchaseOrder->totalCost($fromPostCoding->purchase_order_id);
	}
	
	/*更改采购条目状态
	*
	* @param $data
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	public function changeItemStatus($data){
		$purchaseOrder=new PurchaseOrderModel;
		$changeItemStatu=$this->find($data['purchaseItem_id']);
		$changeItemStatu->status=$data['itemStatus'];
		$changeItemStatu->save();
		if($data['itemStatus']==1){
			$date['status']=1;
			$purchaseOrder->updatePurchaseOrder($changeItemStatu->purchase_order_id,$date);
			}
	}
		
}