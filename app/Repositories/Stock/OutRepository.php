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
use App\Models\Stock\OutModel as Stockout;

class OutRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
        ],
        'update' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
        ]
    ];
    
    public function __construct(Stockout $stockout)
    {
        $this->model = $stockout;
    }
}