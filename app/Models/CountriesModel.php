<?php

namespace App\Models;

use App\Base\BaseModel;

class CountriesModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'cn_name', 'code', 'number', 'parent_id', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['name', 'cn_name'];

    public function countriesSort()
    {
        return $this->belongsTo('App\Models\CountriesSortModel', 'parent_id', 'id');
    }
}
