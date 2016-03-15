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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['type','status','order_id','sku_id','supplier_id','stock','purchase_num','arrival_num','lack_num','platform_id','user_id','update_userid','warehouse_id','purchase_order_id','postage','cost','purchase_cost','examineStatus'];
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
  	
	public function changeItemStatus($data){
		$pitem=new PurchaseItemModel;
		$changeItemStatu=$pitem->find($data['purchaseItem_id']);
		$changeItemStatu->status=$data['itemStatus'];
		$changeItemStatu->save();
		if($data['itemStatus']==1){
			$date['status']=1;
			$this->updatePurchaseOrder($changeItemStatu->purchase_order_id,$date);
			}
	}
	
	public function fromPostCoding($data){
		$pitem=new PurchaseItemModel;
		$fromPostCoding=$pitem->find($data['purchaseItem_id']);
		$fromPostCoding->post_coding=$data['postCoding'];
		$fromPostCoding->postage=$data['postFee'];
		$fromPostCoding->save();
	}
	
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
			$this->where('id',$v)->update(['examineStatus'=>2]);
		}
	
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
	
	/**
     * 导出多张采购单为单张excel
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function purchaseOrdersExcelOut($id)
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