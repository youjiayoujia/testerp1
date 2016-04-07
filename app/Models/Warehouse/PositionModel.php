<?php

namespace App\Models\Warehouse;

use Excel;
use App\Base\BaseModel;
use App\Models\WarehouseModel;

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
    protected $fillable = ['name', 'warehouse_id', 'remark', 'size', 'length', 'width', 'height', 'is_available'];

    // 用于规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouse_positions,name',
            'warehouse_id' => 'required',
            'size' => 'required',
            'length' => 'numeric',
            'width' => 'numeric', 
            'height' => 'numeric'
            ],
        'update' => [
            'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
            'warehouse_id' => 'required',
            'size' => 'required',
            'length' => 'numeric',
            'width' => 'numeric', 
            'height' => 'numeric'
            ]
    ];

    //查询
    public $searchFields = ['name'];
    
    //仓库关联关系
    public function warehouse()
    {
       return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    //库存关联关系
    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'warehouse_position_id', 'id');
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针 
     *
     */
    public function excelProcess($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path.'excelProcess.xls');
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcess($path)
    {
        Excel::load($path, function($reader) {
            $data = $reader->toArray();
            foreach($data as $position)
            {
                if(!WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available' => '1'])->count()) {
                    continue;
                }
                if(PositionModel::where(['name' => trim($position['name'])])->count()) {
                    continue;
                }
                $tmp_warehouse = WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available' => '1'])->first();
                $tmp = $this->create($position);
                $tmp->update(['warehouse_id' => $tmp_warehouse->id]);
            }
        });

        return;
    }
}
