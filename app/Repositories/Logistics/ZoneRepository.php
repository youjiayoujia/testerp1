<?php
/**
 * 物流分区库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:36
 */

namespace App\Repositories\Logistics;

use App\Base\BaseRepository;
use App\Models\Logistics\ZoneModel;

class ZoneRepository extends BaseRepository
{
    protected $searchFields = ['name', 'logistics_id', 'country_id'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'logistics_id' => 'required',
            'country_id' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'logistics_id' => 'required',
            'country_id' => 'required',
        ],
    ];

    public function __construct(ZoneModel $zone)
    {
        $this->model = $zone;
    }
}