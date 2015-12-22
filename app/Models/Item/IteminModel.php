<?php

namespace App\Models;

use App\Base\BaseModel;

class IteminModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'itemins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'amount', 'total_amount', 'remark', 'typeof_itemin', 'typeof_itemin_id'];

    public function getname()
    {
        return $this->belongsTo('App\Models\IteminNameModel', 'typeof_itemin', 'id');
    }
}
