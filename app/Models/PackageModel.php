<?php
namespace App\Models;

use Excel;
use App\Base\BaseModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\SupplierModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['email'];

    public $rules = [
        'create' => ['ordernum' => 'required'],
        'update' => [],
    ];

    protected $fillable = [
        'channel_id',
        'channel_account_id',
        'order_id',
        'warehouse_id',
        'logistics_id',
        'picklist_id',
        'assigner_id',
        'shipper_id',
        'type',
        'status',
        'cost',
        'cost1',
        'weight',
        'actual_weight',
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
        'remark',
        'logistic_assigned_at',
        'printed_at',
        'shipped_at',
        'delivered_at',
        'created_at',
        'is_tonanjing',
        'is_over',
    ];

    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assigner_id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function picklist()
    {
        return $this->belongsTo('App\Models\PickListModel', 'picklist_id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id');
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
        $arr = config('package');
        return $arr[$this->status];
    }

    public function getShippingLimitsAttribute()
    {
        $packageLimits = collect();
        foreach ($this->items as $packageItem) {
            $packageLimit = $packageItem->item->product->package_limit;
            $packageLimits = $packageLimits->merge(explode(",", $packageLimit));
        }
        return $packageLimits->unique();
    }

    /**
     * 判断包裹是否能分配物流
     */
    public function canAssignLogistics()
    {
        //判断订单状态
        if ($this->status != 'NEW') {
            return false;
        }

        //判断是否自动发货
        if (!$this->is_auto) {
            return false;
        }
        return true;
    }

    /**
     * 自动分配物流方式
     */
    public function assignLogistics()
    {
        if ($this->canAssignLogistics()) {
            //匹配物流方式
            $weight = $this->weight; //包裹重量
            $amount = $this->order->amount; //订单金额
            $amountShipping = $this->order->amount_shipping; //订单运费
            $celeAdmin = $this->order->cele_admin;
            //是否通关
            if ($amount > $amountShipping && $amount > 0.1 && $celeAdmin == null) {
                $isClearance = 1;
            } else {
                $isClearance = 0;
            }
            $rules = RuleModel::
            where('weight_from', '<=', $weight)->where('weight_to', '>=', $weight)
                ->where('order_amount', '>=', $amount)
                ->where(['is_clearance' => $isClearance])
                ->orderBy('priority', 'desc')
                ->get();
            foreach ($rules as $rule) {
                //是否在物流方式国家中
                $countries = explode(",", $rule->country);
                if (!in_array($this->shipping_country, $countries)) {
                    continue;
                }
                //是否有物流限制
                $limits = explode(",", $rule->logistics->limit);
                if ($this->shipping_limits->intersect($limits)->count() > 0) {
                    continue;
                }
                echo 'here'.$rule->id;
                //物流查询链接
                $trackingUrl = $rule->logistics->url;
                return $this->update([
                    'status' => 'ASSIGNED',
                    'logistics_id' => $rule->logistics->id,
                    'tracking_link' => $trackingUrl,
                    'logistics_assigned_at' => date('Y-m-d H:i:s')
                ]);
            }
            return $this->update([
                'status' => 'ASSIGNFAILED',
                'logistics_assigned_at' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    /**
     * 判断包裹是否能物流下单
     */
    public function canPlaceLogistics()
    {
        //判断订单状态
        if ($this->status != 'ASSIGNED') {
            return false;
        }

        //判断是否自动发货
        if (!$this->is_auto) {
            return false;
        }
        return true;
    }

    public function placeLogistics()
    {
        if ($this->canPlaceLogistics()) {
            $trackingNo = $this->logistics->placeOrder($this->id);
            if ($trackingNo) {
                return $this->update([
                    'status' => 'PROCESSING',
                    'tracking_no' => $trackingNo,
                    'logistics_order_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        return false;
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
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path . 'excelProcess.xls');
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach ($arr as $key => $content) {
            $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
            $content['logistics_id'] = iconv('gb2312', 'utf-8', trim($content['logistics_id']));
            $content['tracking_no'] = iconv('gb2312', 'utf-8', trim($content['tracking_no']));
            if (!LogisticsModel::where(['logistics_type' => $content['logistics_id']])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_logistics = LogisticsModel::where(['logistics_type' => $content['logistics_id']])->first();
            $tmp_package = $this->where('id', $content['package_id'])->first();
            if (!$tmp_package || $tmp_package->is_auto || $tmp_package->status != 'PROCESSING') {
                $error[] = $key;
                continue;
            }
            $this->find($content['package_id'])->update([
                'logistics_id' => $tmp_logistics->id,
                'tracking_no' => $content['tracking_no'],
                'status' => 'SHIPPED',
                'shipped_at' => date('Y-m-d G:i:s', time()),
                'shipper_id' => '2'
            ]);
            foreach ($this->find($content['package_id'])->items as $packageitem) {
                $packageitem->orderItem->update(['status' => 'SHIPPED']);
            }
        }

        return $error;
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针
     *
     */
    public function excelProcessFee($file, $type)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcessFee($path . 'excelProcess.xls', $type);
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcessFee($path, $type)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach ($arr as $key => $content) {
            $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
            $content['cost'] = iconv('gb2312', 'utf-8', trim($content['cost']));
            $tmp_package = $this->where('id', $content['package_id'])->first();
            if (!$tmp_package || $tmp_package->status != 'SHIPPED') {
                $error[] = $key;
                continue;
            }
            if ($type == 1) {
                $this->find($content['package_id'])->update(['cost' => $content['cost']]);
            } else {
                $this->find($content['package_id'])->update(['cost1' => $content['cost']]);
            }
        }

        return $error;
    }

    /**
     * 将arr转换成相应的格式
     *
     * @param $arr type:array
     * @return array
     *
     */
    public function transfer_arr($arr)
    {
        $buf = [];
        foreach ($arr as $key => $value) {
            $tmp = [];
            if ($key != 0) {
                foreach ($value as $k => $v) {
                    $tmp[$arr[0][$k]] = $v;
                }
                $buf[] = $tmp;
            }
        }

        return $buf;
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
        foreach ($packages as $package) {
            if (!array_key_exists($package->logistic->logistics_supplier_id, $arr)) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            if (!array_key_exists($package->logistic_id, $arr[$package->logistic->logistics_supplier_id])) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            array_push($arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'],
                $package->id);
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
        foreach ($arr as $key1 => $value1) {
            $i = 0;
            $k++;
            foreach ($value1 as $key2 => $value2) {
                foreach ($value2['package_id'] as $key3 => $value3) {
                    $i++;
                    if ($i == 1 && $k != 1) {
                        $rows[] = [
                            '供货商' => '',
                            '物流方式' => '',
                            '发货日期' => '',
                            '运单号' => '',
                            '重量' => '',
                            '总包裹数' => '',
                            '总重量' => '',
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
                    if ($i == 1) {
                        $rows[$j] += ['总包裹数' => $value2['quantity']];
                        $rows[$j] += ['总重量' => $value2['weight']];
                    }
                    $j++;
                }
            }
        }
        $name = '发货复查';
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '发货复查';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}
