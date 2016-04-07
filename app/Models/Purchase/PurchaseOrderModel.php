<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;
use Maatwebsite\Excel\Facades\Excel; 

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
    public $searchFields = ['id', 'supplier_id','warehouse_id','user_id'];
    
	 
    protected $fillable = ['type','status','supplier_id','user_id','update_userid','warehouse_id','costExamineStatus','examineStatus','post_coding','total_postage','total_purchase_cost','close_status','purchase_userid'];
	public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
	
	public function updatePurchaseOrder($id,$data){
		 $PurchaseOrder=$this->find($id);
		 foreach($data as $key=>$v){
			 $PurchaseOrder->$key=$v;
		 }
		 	$PurchaseOrder->save();
	}
	/*取消订单
	*
	* @param $id
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	public function cancelOrderItems($id){
		$purchaseItem=new PurchaseItemModel;
		$purchaseItem->where('purchase_order_id',$id)->update(['active'=>0,'active_status'=>0,'remark'=>'','arrival_time'=>'','purchase_order_id'=>0]);
		$this->destroy($id);
	}
	
	/**
     * 导出单张采购单为单张excel
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function purchaseOrderExcelOut($id)
	{
		$name='采购单'.$id;
		$purchaseItemModel= new PurchaseItemModel;
		$res=$purchaseItemModel->where('purchase_order_id',$id)->get();
		$rows ='';
		foreach($res as $key=>$vo){
			$supplier_province=$vo->supplier->province;
			$supplier_city=$vo->supplier->city;
			$supplier_address=$vo->supplier->address;
			$rows[$key]['id']=$vo->id;
			$rows[$key]['sku']=$vo->sku;
			$rows[$key]['采购单ID']=$vo->order_item_id;
			$rows[$key]['产品名']=$vo->item->product->c_name;
			$rows[$key]['供应商SKU']=$vo->item->supplier_id;
			$rows[$key]['采购单审核状态']=config('purchase.purchaseOrder.examineStatus.'.$vo->purchaseOrder->examineStatus);
			$rows[$key]['采购需求']=config('purchase.purchaseOrder.status.'.$vo->status);
			$rows[$key]['采购数量']=$vo->purchase_num;
			$rows[$key]['到货数量']=$vo->arrival_num;
			$rows[$key]['仍需采购数量']=$vo->lack_num;
			$rows[$key]['供应商链接']='http://'.$vo->supplier->url;
			$rows[$key]['供应商商名']=$vo->supplier->name;
			$rows[$key]['供应商电话']=$vo->supplier->telephone;
			$rows[$key]['供应商地址']=$supplier_province.$supplier_city.$supplier_address;
			$rows[$key]['审核单价']=$vo->cost;
		
		}
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购单';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('xls');	
		}
	
	/**
     * 导出多张采购单为单张excel
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function purchaseOrdersExcelIn($id)
	{
		$name='采购单'.$id;
		$purchaseItemModel= new PurchaseItemModel;
		$res=$purchaseItemModel->where('purchase_order_id',$id)->get();
		$rows ='';
		foreach($res as $key=>$vo){
			$supplier_province=$vo->supplier->province;
			$supplier_city=$vo->supplier->city;
			$supplier_address=$vo->supplier->address;
			$rows[$key]['id']=$vo->id;
			$rows[$key]['sku_id']=$vo->purchaseItem->sku;
			$rows[$key]['采购单ID']=$vo->order_id;
			$rows[$key]['产品名']=$vo->purchaseItem->product->c_name;
			$rows[$key]['供应商SKU']=$vo->purchaseItem->supplier_id;
			$rows[$key]['采购单审核状态']=config('purchase.purchaseOrder.examineStatus.'.$vo->purchaseOrder->examineStatus);
			$rows[$key]['采购需求']=config('purchase.purchaseOrder.status.'.$vo->status);
			$rows[$key]['采购数量']=$vo->purchase_num;
			$rows[$key]['到货数量']=$vo->arrival_num;
			$rows[$key]['仍需采购数量']=$vo->lack_num;
			$rows[$key]['供应商链接']='http://'.$vo->supplier->url;
			$rows[$key]['供应商商名']=$vo->supplier->name;
			$rows[$key]['供应商电话']=$vo->supplier->telephone;
			$rows[$key]['供应商地址']=$supplier_province.$supplier_city.$supplier_address;
			$rows[$key]['审核单价']=$vo->cost;
		
		}
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购单';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('xls');	
		}
}