<?php
/**
 * 物流分配规则模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午2:52
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;
use App\Models\CountriesModel;

class RuleModel extends BaseModel
{
    protected $table = 'logistics_rules';

    public $searchFields = ['country', 'weight_from', 'weight_to', 'order_amount', 'is_clearance', 'priority', 'type_id'];

    protected $fillable = [
        'country',
        'weight_from',
        'weight_to',
        'order_amount',
        'is_clearance',
        'priority',
        'type_id',
    ];

    public $rules = [
        'create' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
        ],
        'update' => [
            'country' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'type_id', 'id');
    }

    /**
     * 遍历国家
     */
    public function country($country)
    {
        $str = '';
        foreach(explode(",", $country) as $value) {
            $countries = CountriesModel::where(['abbreviation' => $value])->get();
            foreach($countries as $country) {
                $val = $country['name'];
                $str = $str.$val.',';
            }
        }
        return substr($str, 0, -1);
    }

}