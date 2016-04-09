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
	 
    protected $fillable = ['type','status','order_item_id','sku','supplier_id','purchase_num','arrival_num','lack_num','user_id','update_userid','warehouse_id','purchase_order_id','postage','post_coding','storageStatus','purchase_cost','costExamineStatus','active','active_status'];
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
		
}