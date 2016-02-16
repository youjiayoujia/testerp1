<?php

namespace App\Models\Warehouse;

use App\Base\BaseModel;

class PositionModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouse_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'warehouses_id', 'remark', 'size', 'is_available'];

    // 用于规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouse_positions,name',
            'warehouses_id' => 'required',
            'size' => 'required'
            ],
        'update' => [
            'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
            'warehouses_id' => 'required',
            'size' => 'required'
            ]
    ];

    //查询
    protected $searchField = ['name, size, is_available'];
    
    public function warehouse()
    {
       return $this->belongsTo('App\Models\WarehouseModel', 'warehouses_id', 'id');
    }

    /**
     *  获取库位信息 
     *  @param $arr 
     *  @param $field 获取的字段
     *
     *  @return mes
     */
    public function getObj($arr,$field = ['*'])
    {
        return $this->where($arr)->get($field);
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
