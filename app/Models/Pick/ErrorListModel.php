<?php

namespace App\Models\Pick;

use App\Base\BaseModel;

class ErrorListModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'picklist_error_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picklist_id', 'package_id', 'status', 'process_by', 'process_time', 'created_at'];

    public $searchFields = [];
    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function picklist()
    {
        return $this->belongsTo('App\Models\PickListModel', 'picklist_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo('App\Models\PackageModel', 'package_id', 'id');
    }

    public function processByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'process_by', 'id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => [
            ],
            'filterSelects' => [
            ],
            'sectionSelect' => [
            ],
            'relatedSearchFields' => [
            ],
            'selectRelatedSearchs' => [
            ]
        ];
    }
}
