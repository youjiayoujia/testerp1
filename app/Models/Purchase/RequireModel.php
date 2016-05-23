<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;

class RequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requires';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id','sku'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    } 
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	
}