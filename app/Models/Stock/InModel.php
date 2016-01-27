<?php

namespace App\Models\Stock;

use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;

class InModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_ins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'sku', 'amount', 'total_amount', 'remark', 'warehouses_id', 'warehouse_positions_id', 'type', 'relation_id'];


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

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_positions_id', 'id');
    }

    /**
     *  make the accessor. 
     *  get the name by key in config.
     *
     *  @return name(by type)
     */
    public function getTypeNameAttribute()
    {
        $buf = config('in.in');
        return $buf[$this->type];
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
        $buf = ItemModel::all()->toArray();
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
        $buf =  PositionModel::all()->toArray();
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
