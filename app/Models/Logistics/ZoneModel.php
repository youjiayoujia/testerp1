<?php
/**
 * 物流分区模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:22
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ZoneModel extends BaseModel
{
    protected $table = 'logistics_zones';

    protected $fillable = [
        'zone',
        'logistics_id',
        'country_id',
        'shipping_id',
        'price',
        'other_price',
        'fixed_weight',
        'fixed_price',
        'continued_weight',
        'continued_price',
        'other_fixed_price',
        'other_scale_price',
        'discount',
    ];

    protected $searchFields = ['zone', 'logistics_id', 'country_id', 'shipping_id'];

    public $rules = [
        'create' => [
            'zone' => 'required',
            'logistics_id' => 'required',
            'country_id' => 'required',
            'shipping_id' => 'required',
        ],
        'update' => [
            'zone' => 'required',
            'logistics_id' => 'required',
            'country_id' => 'required',
            'shipping_id' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountryModel', 'country_id', 'id');
    }

}