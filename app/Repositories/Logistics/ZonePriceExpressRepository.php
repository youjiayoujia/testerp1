<?php
/**
 * 物流分区报价(快递)库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 下午3:37
 */

namespace App\Repositories\Logistics;

use App\Base\BaseRepository;
use App\Models\Logistics\ZonePriceExpressModel;

class ZonePriceExpressRepository extends BaseRepository
{
    protected $searchFields = ['name', 'fixed_weight', 'continued_weight'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'shipping' => 'required',
            'fixed_weight' => 'required',
            'fixed_price' => 'required',
            'continued_weight' => 'required',
            'continued_price' => 'required',
            'other_fixed_price' => 'required',
            'other_scale_price' => 'required',
            'discount' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'shipping' => 'required',
            'fixed_weight' => 'required',
            'fixed_price' => 'required',
            'continued_weight' => 'required',
            'continued_price' => 'required',
            'other_fixed_price' => 'required',
            'other_scale_price' => 'required',
            'discount' => 'required',
        ],
    ];

    public function __construct(ZonePriceExpressModel $zonePriceExpress)
    {
        $this->model = $zonePriceExpress;
    }

}