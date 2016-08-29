<?php
/**
 * 分类渠道税率
 * Created by PhpStorm.
 * User: jiangdi
 * Date: 2016/8/29
 * Time: 18:02
 */
namespace App\Models\Channel;

use App\Base\BaseModel;

class CatalogRatesModel extends BaseModel{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catalog_rates_channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    //protected $guarded = [];

    public $searchFields = ['name' => '渠道名称'];

    protected $fillable = [
        'name'
    ];

    protected $rules = [
        'create' => ['name' => 'required|unique:channels,name'],
        'update' => ['name' => 'required|unique:channels,name,{id}']
    ];
}