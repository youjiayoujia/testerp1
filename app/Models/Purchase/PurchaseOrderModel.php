<?php
namespace App\Models\Purchase;
use Exception;
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
    
	 
    protected $fillable = ['type','status','supplier_id','user_id','update_userid','warehouse_id','costExamineStatus','examineStatus','post_coding','total_postage','total_purchase_cost','close_status','purchase_userid','start_buying_time','arrival_time','assigner'];
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
	
	public function allPurchaseExcelOut()
	{
		$name='采购单';
		$assigner=12;
		$purchaseOrderIds=$this->select('id')->where('assigner',$assigner)->get()->toArray();
		$res=PurchaseItemModel::whereIn('purchase_order_id',$purchaseOrderIds)->orderBy('supplier_id','desc')->get();
		$rows ='';
		foreach($res as $key=>$vo){
			$supplier_province=$vo->supplier->province;
			$supplier_city=$vo->supplier->city;
			$supplier_address=$vo->supplier->address;
			$rows[$key]['PurcahseOrderID']=$vo->purchase_order_id;
			$rows[$key]['PurchaseItemID']=$vo->id;
			$rows[$key]['status']=config("purchase.purchaseItem.status.".$vo->status);
			$rows[$key]['sku']=$vo->sku;
			$rows[$key]['purchase_qty']=$vo->purchase_num;
			$rows[$key]['purchase_price']=$vo->purchase_cost;
			$rows[$key]['item_name']=iconv("UTF-8", "gb2312" ,"'".$vo->item->product->c_name."'");
			$rows[$key]['supplier_SKU']=$vo->item->supplier_sku;
			$rows[$key]['remark']=$vo->remark;
			$rows[$key]['supplier_name']=iconv("UTF-8", "gb2312" ,"'".$vo->supplier->name."'");
			$rows[$key]['supplier_link']='http://'.$vo->supplier->url;
			$rows[$key]['purchas_address']=iconv("UTF-8", "gb2312" ,"'".$supplier_province.$supplier_city.$supplier_address."'");
			$rows[$key]['user_id']=iconv("UTF-8", "gb2312" ,$vo->user_id);
			$rows[$key]['supplier_telephone']=$vo->supplier->telephone;
			$rows[$key]['tracking']=$vo->post_coding;
			$rows[$key]['model']=$vo->item->product->model;
		}
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购单';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('csv');	
		}
	
	/**
     * 导出多张采购单为单张excel
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function noArrivalOut()
	{
		$name='采购单';
		$assigner=12;
		$purchaseOrderIds=$this->select('id')->where('assigner',$assigner)->get()->toArray();
		$res=PurchaseItemModel::whereIn('purchase_order_id',$purchaseOrderIds)->where('start_buying_time','<',date('Y-m-d H:i:s',time()-3600*24*3))->orderBy('supplier_id','desc')->get();
		$rows ='';
		foreach($res as $key=>$vo){
			$supplier_province=$vo->supplier->province;
			$supplier_city=$vo->supplier->city;
			$supplier_address=$vo->supplier->address;
			$rows[$key]['PurcahseOrderID']=$vo->purchase_order_id;
			$rows[$key]['PurchaseItemID']=$vo->id;
			$rows[$key]['status']=mb_convert_encoding(config("purchase.purchaseItem.status.".$vo->status), 'gb2312', 'utf-8');
			$rows[$key]['sku']=mb_convert_encoding("大傻逼", 'gb2312', 'utf-8');
			$rows[$key]['purchase_qty']=$vo->purchase_num;
			$rows[$key]['purchase_price']=$vo->purchase_cost;
			$rows[$key]['item_name']=mb_convert_encoding($vo->item->product->c_name, 'gb2312', 'utf-8');
			$rows[$key]['supplier_SKU']=$vo->item->supplier_sku;
			$rows[$key]['remark']=$vo->remark;
			$rows[$key]['supplier_name']=mb_convert_encoding($vo->supplier->name, 'gb2312', 'utf-8');
			$rows[$key]['supplier_link']='http://'.$vo->supplier->url;
			//$rows[$key]['purchas_address']=iconv( "gb2312" ,"UTF-8",$supplier_province.$supplier_city.$supplier_address);
			$rows[$key]['user_id']=$vo->user_id;
			$rows[$key]['supplier_telephone']=$vo->supplier->telephone;
			$rows[$key]['tracking']=$vo->post_coding;
			$rows[$key]['model']=$vo->item->product->model;
			$rows[$key]['assigner']=$vo->purchaseOrder->assigner;
			$rows[$key]['create_time']=$vo->created_at;
		}
		//var_dump($rows);exit;
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购单';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('csv');
		}
}