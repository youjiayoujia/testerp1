<?php
/**
 * 入库操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/22
 * Time:10:48
 *
 */
namespace App\Repositories\Item;

use App\Base\BaseRepository;
use App\Models\Item\IteminModel as Itemin;

class IteminRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'total_amount' => 'required|integer',
        ],
        'update' => [
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'total_amount' => 'required|integer',
        ]
    ];
    
    public function __construct(Itemin $itemin)
    {
        $this->model = $itemin;
    }
}