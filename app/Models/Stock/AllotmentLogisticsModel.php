<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AllotmentLogisticsModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'allotment_logistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['allotments_id', 'type', 'code', 'fee', 'created_at'];
}
