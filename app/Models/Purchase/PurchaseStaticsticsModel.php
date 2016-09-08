<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchaseStaticsticsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_staticstics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    public $searchFields = ['id'=>'id'];
    

}