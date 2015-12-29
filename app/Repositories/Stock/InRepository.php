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
namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\InModel as Stockin;
use App\Models\ItemModel as Item;
use App\Models\Warehouse\PositionModel as Position;

class InRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['sku'];

    // 规则验证
    public $rules = [
        'create' => [
            'item_id' => 'required',
            'sku' => 'required|max:128',
            'amount' => 'required|numeric',
            'warehouses_id' => 'required|integer',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
        ],
        'update' => [
            'item_id' => 'required',
            'sku' => 'required|max:128',
            'amount' => 'required|integer',
            'warehouses_id' => 'required|integer     ',
            'warehouse_positions_id' => 'required|integer',
            'total_amount' => 'required|integer',
        ]
    ];
    
    public function __construct(Stockin $stockin)
    {
        $this->model = $stockin;
    }

    /**
     * 通过sku  获取对应的item_id
     *
     * @param $sku sku值
     * @return ''|id
     *
     */
    public function getItemId($sku)
    {
        $buf = Item::all()->toArray();
        foreach($buf as $item)
            if($item['sku'] == $sku)
                return $item['id'];
        return '';
    }

    /**
     * 通过id,获取库位信息
     *  
     * @param $id integer 仓库id
     * @return array [key|name]
     *
     */
    public function getPosition($id)
    {
        $buf =  Position::all()->toArray();
        $arr = [];
        $i = 0;
        foreach($buf as $line)
            if($line['warehouses_id'] == $id) {
                foreach($line as $key => $val)
                    $arr[$i][$key] = $val;
                $i++;
            }

        return $arr;
    }

}