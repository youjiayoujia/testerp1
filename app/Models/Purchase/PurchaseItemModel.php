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
	public function purchaseItem()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku_id','sku');
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
     * 创建采购需求
     *
     * @param $data
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchasestore($data)
	{
		$data['lack_num']=$data['purchase_num'];
		$item=new ItemModel();
		$productItem=$item->where('sku',$data['sku_id'])->first();
		$data['supplier_id']=$productItem->supplier_id;
		$data['cost']=$productItem['purchase_price'];
		$data['stock']=1;//$productItem['inventory'];
		$this->create($data);
	}
	
	/**
     * 跟新采购需求
     *
     * @param $id,$data
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
 	public function purchaseItemUpdate($id,$data)
	{
		$purchaseItem=$this->find($id);
        $arrival_num=$purchaseItem->arrival_num;
		$purchaseItem->purchase_num=$data['purchase_num'];
		$purchaseItem->lack_num=$data['purchase_num']-$arrival_num;
		$purchaseItem->save();
	}
	
	/**
     *批量创建采购单
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseOrderCreate()
	{
		$warehouse_supplier=$this->select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();	
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$purchaseOrderModel =new PurchaseOrderModel;
				$data['warehouse_id']=$v['warehouse_id'];		 
				$data['supplier_id']=$v['supplier_id'];
				$purchaseOrder=$purchaseOrderModel->create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
					$this->where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->update(['purchase_order_id'=>$purchaseOrderId]); 
				}				 
			}
			return true;
		}else{
			return false;
			}
		$warehouse_nosupplier=$this->select('id','warehouse_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id',0)->get()->toArray();
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$purchaseOrderModel =new PurchaseOrderModel;
				$data['warehouse_id']=$v['warehouse_id'];	
				$purchaseOrder=$purchaseOrderModel->create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
				$purchaseItem=$this->find($v['id']);
				$purchaseItem->purchase_order_id=$v['purchase_order_id'];
				$purchaseItem->save();
				}				 
			}
			return true;
		}
		
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
		
	/*取消订单
	*
	* @param $data
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	
	public function cancelOrderItem($id){
		$purchaseItem=$this->find($id);
		$purchaseItem->purchase_order_id=0;
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