<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchaseCrontabsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_crontabs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }
}