<?php
namespace App\Models\Purchase;
use Excel;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchasePostageModel;

class PurchaseItemArrivalLogModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_item_arrival_logs';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			
        ]
    ];
    public $searchFields = ['sku'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['id','sku','purchase_item_id','arrival_num','good_num','bad_num','quality_time'];
	
}