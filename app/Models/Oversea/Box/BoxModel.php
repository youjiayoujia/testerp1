<?php

namespace App\Models\Oversea\Box;

use App\Base\BaseModel;

class BoxModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversead_boxes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['boxnum','parent_id','volumn','logistics_id','tracking_no','weight','created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['boxnum' => '箱号'];
}
