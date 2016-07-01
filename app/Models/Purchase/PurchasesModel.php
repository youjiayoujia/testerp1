<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchasesModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    public $searchFields = ['id','sku','status'];
    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }

}