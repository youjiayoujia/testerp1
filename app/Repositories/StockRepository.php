<?php
/**
 * 库存操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/30
 * Time:14:48
 *
 */
namespace App\Repositories;

use DB;
use App\Base\BaseRepository;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;

class StockRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required',
            'warehouses_id' => 'required|numeric',
            'warehouse_positions_id' => 'required|numeric',
            'all_amount' => 'required|numeric',
            'available_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',    
        ],
        'update' => [
            'sku' => 'required',
            'warehouses_id' => 'required|numeric',
            'warehouse_positions_id' => 'required|numeric',
            'all_amount' => 'required|numeric',
            'available_amount' => 'required|numeric',
            'total_amount' => 'required|numeric', 
        ],
    ];
    
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
    }

    /**
     * get the unit_cost by the position 
     *
     *  @return unit_price
     *
     */
    
}