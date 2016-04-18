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
    
	 
    protected $fillable = ['type','status','supplier_id','user_id','update_userid','warehouse_id','costExamineStatus','examineStatus','post_coding','total_postage','total_purchase_cost','close_status','purchase_userid','start_buying_time','arrival_time'];
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
	
	public function excelOrdersOut($purchaseOrderIds)
	{
		$name='采购单';
		$rows='';
		foreach($purchaseOrderIds as $k=>$v){
		$res=PurchaseItemModel::where('purchase_order_id',$v->purchase_order_id)->get();
		foreach($res as $key=>$vo){
			$supplier_province=$vo->supplier->province;
			$supplier_city=$vo->supplier->city;
			$supplier_address=$vo->supplier->address;
			$rows[$k][$key]['id']=$vo->id;
			$rows[$k][$key]['sku']=$vo->item->sku;
			$rows[$k][$key]['采购单ID']=$vo->order_item_id;
			$rows[$k][$key]['产品名']=$vo->item->product->c_name;
			$rows[$k][$key]['供应商SKU']=$vo->item->supplier_id;
			//$rows[$k][$key]['采购单审核状态']=config('purchase.purchaseOrder.examineStatus.'.$vo->purchaseOrder->examineStatus);
			$rows[$k][$key]['采购需求']=config('purchase.purchaseOrder.status.'.$vo->status);
			$rows[$k][$key]['采购数量']=$vo->purchase_num;
			$rows[$k][$key]['到货数量']=$vo->arrival_num;
			$rows[$k][$key]['仍需采购数量']=$vo->lack_num;
			$rows[$k][$key]['供应商链接']='http://'.$vo->supplier->url;
			$rows[$k][$key]['供应商商名']=$vo->supplier->name;
			$rows[$k][$key]['供应商电话']=$vo->supplier->telephone;
			$rows[$k][$key]['供应商地址']=$supplier_province.$supplier_city.$supplier_address;
			$rows[$k][$key]['审核单价']=$vo->cost;
		
		}
		}
		Excel::create($name, function($excel) use ($rows) {
			foreach($rows as $k=>$row){
			$excel->sheet('采购单'.$k, function($sheet) use ($row) {
				$sheet->fromArray($row);
			});
			}
		})->download('xls');	
		}
}