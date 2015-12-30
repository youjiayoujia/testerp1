<?php
/**
 * 跟踪号库类
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/28
 * Time: 上午9:46
 */

namespace App\Repositories\Logistics;

use App\Base\BaseRepository;
use App\Models\Logistics\CodeModel as Code;

class CodeRepository extends BaseRepository
{
    protected $searchFields = ['logistics_id', 'code', 'status', 'package_id'];

    public $rules = [
        'create' => [
            'logistics_id' => 'required',
            'code' => 'required',
            'status' => 'required',
            'package_id' => 'required',
            'used_at' => 'required',
        ],
        'update' => [
            'logistics_id' => 'required',
            'code' => 'required',
            'status' => 'required',
            'package_id' => 'required',
            'used_at' => 'required',
        ],
    ];

    public function __construct(Code $code)
    {
        $this->model = $code;
    }

}