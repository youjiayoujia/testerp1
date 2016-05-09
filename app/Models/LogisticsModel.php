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
use App\Models\Logistics\LimitsModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logisticses';

    public $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    protected $fillable = [
        'short_code',
        'logistics_type',
        'species',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'url',
        'docking',
        'pool_quantity',
        'is_enable',
        'limit',
    ];


    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'species' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'docking' => 'required',
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
        Excel::load($filePath, function ($reader) {
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

    public function logisticsLimit()
    {
        return $this->belongsTo('App\Models\Logistics\LimitsModel', 'limit', 'id');
    }

    public function codes()
    {
        return $this->hasMany('App\Models\Logistics\CodeModel', 'logistics_id');
    }

    public function getDockingNameAttribute()
    {
        $arr = config('logistics.docking');
        return $arr[$this->docking];
    }

    /**
     * 物流商下单
     * todo:分方式下单
     */
    public function placeOrder($packageId)
    {
        $code = $this->codes->where('status', '0')->first();
        if ($code) {
            $code->update([
                'status' => 1,
                'package_id' => $packageId,
                'used_at' => date('y-m-d', time())
            ]);
            return $code->code;
        }
        return false;

    }

    /**
     * 遍历物流限制
     */
    public function limit($limit)
    {
        $str = '';
        foreach (explode(",", $limit) as $value) {
            $limits = LimitsModel::where(['id' => $value])->get();
            foreach ($limits as $limit) {
                $val = $limit['name'];
                $str = $str . $val . ',';
            }
        }
        return substr($str, 0, -1);
    }
}