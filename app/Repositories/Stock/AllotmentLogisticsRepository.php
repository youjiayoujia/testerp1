<?php
/**
 * 出库操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/22
 * Time:11:07
 *
 */

namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\AllotmentLogisticsModel;

class AllotmentLogisticsRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'allotmentns_id' => 'required|max:128',
            'type' => 'required',
            'code' => 'required',
            'fee' => 'required|numeric',
        ],
        'update' => [
            'allotmentns_id' => 'required|max:128',
            'type' => 'required',
            'code' => 'required',
            'fee' => 'required|numeric',
        ]
    ];
    
    public function __construct(AllotmentLogisticsModel $allotmentlogistics)
    {
        $this->model = $allotmentlogistics;
    }
}