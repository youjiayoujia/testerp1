<?php
/**
 * 库存调整操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/22
 * Time:14:25
 *
 */

namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\AdjustmentModel;

class AdjustmentRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['adjust_form_id', 'sku'];

    // 规则验证
    public $rules = [
        'update' => [
            'sku' => 'required',
            'amount' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'adjust_time' =>'required|date',
        ]
    ];
    
    public function __construct(AdjustmentModel $adjustment)
    {
        $this->model = $adjustment;
    }

}