<?php

namespace App\Models\Oversea\Allotment;

use App\Base\BaseModel;

class AllotmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversead_allotments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['warehouse_id', 'name', 'transport', 'formula', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['name' => '物流名'];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }
}
