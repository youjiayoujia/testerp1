<?php
namespace App\Models;

use Excel;
use App\Base\BaseModel;
use App\Models\LogisticsModel;
use App\Models\Logistics\SupplierModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['email'];

    public $rules = [
        'create' => ['order_id' => 'required'],
        'update' => [],
    ];

    protected $fillable = [
        'order_id',
        'logsitic_id',
        'picklist_id',
        'assigner_id',
        'status',
        'cost',
        'weight',
        'length',
        'width',
        'height',
        'tracking_no',
        'tracking_link',
        'email',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_address1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'is_auto',
        'is_auto_logistic',
        'remark',
        'logistic_assigned_at',
        'printed_at',
        'shipped_at',
        'delivered_at',
    ];

    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assigner_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function logistic()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistic_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Package\ItemModel', 'package_id');
    }

    public function listItemPackages()
    {
        return $this->hasMany('App\Models\Pick\ListItemPackageModel', 'package_id', 'id');
    }

    public function manualLogistics()
    {
        return $this->hasMany('App\Models\Package\LogisticModel', 'package_id', 'id');
    }
    
    public function getStatusNameAttribute()
    {
        $arr = config('pick.package');
        return $arr[$this->status];
    }

    /**
     * 根据包裹封装数组 
     *
     * @param $packages 包裹
     * @return none
     *
     */
    public function exportData($packages)
    {
        $arr = [];
        foreach($packages as $package)
        {
            if(!array_key_exists($package->logistic->logistics_supplier_id, $arr)) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            if(!array_key_exists($package->logistic_id, $arr[$package->logistic->logistics_supplier_id])) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            array_push($arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'], $package->id);
            $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] += 1;
            $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] += $package->weight;
        }
        $this->loadExcel($arr);
    }

    /**
     * 根据封装好的数组，生成excel 
     *
     * @param $arr array
     * @return none
     *
     */
    public function loadExcel($arr)
    {
        $j = 0;
        $k = 0;
        foreach($arr as $key1 => $value1) 
        {
            $i = 0;
            $k++;
            foreach($value1 as $key2 => $value2)
            {
                foreach($value2['package_id'] as $key3 => $value3)
                {
                    $i++;
                    if($i == 1 && $k != 1)
                    {
                        $rows[] = [
                            '供货商' => '',
                            '物流方式' => '',
                            '发货日期' => '',
                            '运单号' => '',
                            '重量' => '',
                            '总包裹数'=> '',
                            '总重量'=>'',
                        ];  
                        $j++;
                    }
                    $rows[] = [
                        '供货商' => SupplierModel::find($key1)->name,
                        '物流方式' => LogisticsModel::find($key2)->logistics_type,
                        '发货日期' => iconv('utf-8', 'gb2312', PackageModel::find($value3)->shipped_at),
                        '运单号' => PackageModel::find($value3)->tracking_no,
                        '重量' => PackageModel::find($value3)->weight,
                    ]; 
                    if($i == 1) {
                        $rows[$j] += ['总包裹数'=>$value2['quantity']];
                        $rows[$j] += ['总重量'=>$value2['weight']];
                    }
                    $j++;
                }
            }
        }
        $name = '发货复查';
        Excel::create($name, function($excel) use ($rows){
            $nameSheet='发货复查';
            $excel->sheet($nameSheet, function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}
