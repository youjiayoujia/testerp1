<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;

class PurchasePostageModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_postages';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id','purchase_item_id','purchase_order_id','post_coding','postage'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['id','purchase_item_id','purchase_order_id','post_coding','postage']; 	
	
}