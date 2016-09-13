<?php
/**
 * 物流对账出错产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ShipmentCostErrorModel extends BaseModel
{
    protected $table = 'shipment_cost_errors';

    protected $fillable = [
    	'parent_id',
    	'hang_num',
    	'channel_name',
        'remark',
        'created_at'
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
                'parent' => ['shipmentCostNum']
            ],
            'filterFields' => ['hang_num', 'channel_name'],
            'filterSelects' => [
            ],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => [],
        ];
    }

    public $searchFields = ['hang_num' => '挂号码'];

    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach ($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Package\ShipmentCostModel', 'parent_id', 'id');
    }
}