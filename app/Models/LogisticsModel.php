<?php
/**
 * 物流方式模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:13
 */

namespace App\Models;

use App\Base\BaseModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logisticses';

    protected $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    protected $fillable = [
        'short_code',
        'logistics_type',
        'shipping',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'url',
        'api_docking',
        'is_enable'
    ];


    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'shipping' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'shipping' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
    ];


    /**
     * 批量倒入号码池
     *
     * @param $file 导入所需的Excel文件
     *
     */
    public function batchImport($file)
    {
        $filePath = '' . $file;
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Logistics\SupplierModel', 'logistics_supplier_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

}