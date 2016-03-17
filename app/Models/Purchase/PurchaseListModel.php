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
	public function purchaseItem()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku_id');
    }
	public function productAbnormal()
    {
        return $this->belongsTo('App\Models\Purchase\ProductAbnormalModel', 'sku_id','sku_id');
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
		$productItem=$item->find($data['sku_id']);
		$data['supplier_id']=$productItem->supplier_id;
		$data['cost']=$productItem['purchase_price'];
		if($data['cost']>0){
			$data['costExamineStatus']=2;
			}
		$this->create($data);
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
		if($data['active']>0){
			$productAbnormal=new ProductAbnormalModel;
			$abnormal['sku_id']=$purchaseItem->sku_id;
			$abnormal['type']=$data['active'];
			switch($data['active']){
				case 1:	
					$productAbnormal->create($abnormal);
					break;
				case 2:
					$abnormal['arrival_time']=$data['arrival_time'];
					$productAbnormal->create($abnormal);
					break;
				case 3:
					$abnormal['remark']=$data['remark'];
					$productAbnormal->create($abnormal);
					break;
				case 4:
					$image=$this->find($id);
					$abnormal['image_id']=$image->purchaseItem->product->default_image;
					$productAbnormal->create($abnormal);
					break;		
				}			
			}
		$purchaseItem->active=$data['active'];
		$purchaseItem->status=$data['status'];
		$purchaseItem->costExamineStatus=$data['costExamineStatus'];
		$purchase_num=$purchaseItem->purchase_num;
		$purchaseItem->arrival_num=$data['arrival_num'];	
		$purchaseItem->lack_num=$purchase_num-$data['arrival_num'];
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
		$warehouse_supplier=$this->select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();	
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$purchaseOrderModel =new PurchaseOrderModel;
				$data['warehouse_id']=$v['warehouse_id'];
				$data['supplier_id']=$v['supplier_id'];
				$purchaseOrder=$purchaseOrderModel->create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
				$this->where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->update(['purchase_order_id'=>$purchaseOrderId]); 
				}
			}
			return true;
		}else{
			return false;
			}
	}
	
}