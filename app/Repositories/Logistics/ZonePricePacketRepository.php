<?php
/**
 * 物流分区报价(小包)库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 下午3:34
 */

namespace App\Repositories\Logistics;

use App\Base\BaseRepository;
use App\Models\Logistics\ZonePricePacketModel;

class ZonePricePacketRepository extends BaseRepository
{
    protected $searchFields = ['name', 'price', 'other_price'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'shipping' => 'required',
            'price' => 'required',
            'other_price' => 'required',
            'discount' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'shipping' => 'required',
            'price' => 'required',
            'other_price' => 'required',
            'discount' => 'required',
        ],
    ];

    public function __construct(ZonePricePacketModel $zonePricePacket)
    {
        $this->model = $zonePricePacket;
    }

}