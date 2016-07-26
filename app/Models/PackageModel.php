<?php
namespace App\Models;

use Excel;
use App\Base\BaseModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\SupplierModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\ZoneModel;
use App\Models\LogisticsModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['tracking_no' => '追踪号'];

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
        'logistics_assigned_at',
        'printed_at',
        'shipped_at',
        'delivered_at',
        'created_at',
        'is_tonanjing',
        'is_over',
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
                'channel' => ['name'],
                'channelAccount' => ['account'],
                'order' => ['ordernum'],
            ],
            'filterFields' => ['tracking_no'],
            'filterSelects' => ['status' => config('package'), 'warehouse_id' => $this->getArray('App\Models\WarehouseModel', 'name'), 'logistics_id' => $this->getArray('App\Models\LogisticsModel', 'code')],
            'selectRelatedSearchs' => [
                'order' => ['status' => config('order.status'), 'active' => config('order.active')],
            ],
            'sectionSelect' => [],
        ];
    }

    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }

    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assigner_id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
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

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('package');
        return $arr[$this->status];
    }

    /*******************************************************************************/
    /**
     * @param array $items
     * @return array|bool
     */
    public function setPackageItems()
    {
        if ($this->count() > 1) { //多产品
            $packageItem = $this->setMultiPackageItem();
        } else { //单产品
            $packageItem = $this->setSinglePackageItem();
        }
        return $packageItem;
    }

    //设置单产品订单包裹产品
    public function setSinglePackageItem()
    {
        $packageItem = [];
        $originPackageItem = $this->items->first();
        $quantity = $originPackageItem->quantity;
        if (!$quantity) {
            return false;
        }
        $stocks = $originPackageItem->item->assignStock($quantity);
        var_dump($stocks);
        if ($stocks) {
            foreach ($stocks as $warehouseId => $stock) {
                foreach ($stock as $key => $value) {
                    $packageItem[$warehouseId][$key] = $value;
                    $packageItem[$warehouseId][$key]['order_item_id'] = $originPackageItem->order_item_id;
                    $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                }
            }
        } else {
            return false;
        }
var_dump($packageItem);exit;
        return $packageItem;
    }

    //设置多产品订单包裹产品
    public function setMultiPackageItem()
    {
        $packageItem = [];
        $stocks = [];
        //根据仓库满足库存数量进行排序
        $warehouses = [];
        foreach ($this->items as $originPackageItem) {
            $quantity = $originPackageItem->quantity;
            if (!$quantity) {
                continue;
            }
            $itemStocks = $originPackageItem->item->matchStock($quantity);
            if ($itemStocks) {
                foreach ($itemStocks as $itemStock) {
                    foreach ($itemStock as $warehouseId => $stock) {
                        if (isset($warehouses[$warehouseId])) {
                            $warehouses[$warehouseId] += 1;
                        } else {
                            $warehouses[$warehouseId] = 1;
                        }
                    }
                }
                $stocks[$originPackageItem->order_item_id] = $itemStocks;
            } else {
                return false;
            }
        }
        krsort($warehouses);
        //set package item
        foreach ($stocks as $orderItemId => $itemStocks) {
            foreach ($itemStocks as $type => $itemStock) {
                if ($type == 'SINGLE') {
                    $stock = collect($itemStock)->sortByDesc(function ($value, $key) use ($warehouses) {
                        return $warehouses[$key];
                    })->first();
                    foreach ($stock as $key => $value) {
                        $packageItem[$value['warehouse_id']][$key] = $value;
                        $packageItem[$value['warehouse_id']][$key]['order_item_id'] = $orderItemId;
                        $packageItem[$value['warehouse_id']][$key]['remark'] = 'REMARK';
                    }
                } else {
                    foreach ($itemStock as $warehouseId => $warehouseStock) {
                        foreach ($warehouseStock as $key => $value) {
                            $packageItem[$warehouseId][$key] = $value;
                            $packageItem[$warehouseId][$key]['order_item_id'] = $orderItemId;
                            $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                        }
                    }
                }
            }
        }

        return $packageItem;
    }
    /**********************************************************************************/

    public function getShippingLimitsAttribute()
    {
        $packageLimits = collect();
        foreach ($this->items as $packageItem) {
            $packageLimit = $packageItem->item->product->carriage_limit;
            if ($packageLimit) {
                $packageLimits = $packageLimits->merge(explode(",", $packageLimit));
            }
        }

        return $packageLimits->unique();
    }

    public function getHasPickAttribute()
    {
        $items = $this->items;
        foreach ($items as $item) {
            if ($item->picked_quantity) {
                return true;
            }
        }
        return false;
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

    public function calculateLogisticsFee()
    {
        $zones = ZoneModel::where('logistics_id', $this->logistics_id)->get();
        foreach ($zones as $zone) {
            if ($zone->inZone($this->shipping_country)) {
                $fee = '';
                if ($zone->type == 'first') {
                    if ($this->weight <= $zone->fixed_weight) {
                        $fee = $this->fixed_price;
                    } else {
                        $fee = $this->fixed_price;
                        $weight = $this->weight - $zone->fixed_weight;
                        $fee += ceil($weight / $zone->continued_weight) * $zone->continued_price;
                    }
                    if ($zone->discount_weather_all) {
                        $fee = ($fee + $zone->other_fixed_price) * $zone->discount;
                    } else {
                        $fee = $fee * $zone->discount + $zone->other_fixed_price;
                    }
                    return $fee;
                } else {
                    $sectionPrices = $zone->zone_section_prices;
                    foreach ($sectionPrices as $sectionPrice) {
                        if ($this->weight >= $sectionPrice->weight_from && $this->weight < $sectionPrice->weight_to) {
                            return $sectionPrice->price;
                        }
                    }
                }
            }
        }

        return false;
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
            where(function ($query) use ($weight) {
                $query->where('weight_from', '<=', $weight)
                    ->where('weight_to', '>=', $weight)->orwhere('weight_section', '0');
            })->where(function ($query) use ($amount) {
                $query->where('order_amount_from', '<=', $amount)
                    ->where('order_amount_to', '>=', $amount)->orwhere('order_amount_section', '0');
            })->where(['is_clearance' => $isClearance])
                ->orderBy('priority', 'desc')
                ->get();
            foreach ($rules as $rule) {
                //是否在物流方式国家中
                if ($rule->country_section) {
                    $countries = $rule->rule_countries_through;
                    $flag = 0;
                    foreach ($countries as $country) {
                        if ($country->code == $this->shipping_country) {
                            $flag = 1;
                            break;
                        }
                    }
                    if ($flag == 0) {
                        continue;
                    }
                }
                //是否有物流限制
                if ($rule->limit_section && $this->shipping_limits) {
                    $shipping_limits = $this->shipping_limits->toArray();
                    $limits = $rule->rule_limits_through;
                    foreach ($limits as $limit) {
                        if (in_array($limit->id, $shipping_limits)) {
                            if ($limit->pivot->type == '3') {
                                continue 2;
                            }
                        }
                    }
                }
                //查看对应的物流方式是否是所属仓库
                $warehouse = WarehouseModel::find($this->warehouse_id);
                if (!$warehouse->logisticsIn($rule->type_id)) {
                    continue;
                }
                //物流查询链接
                $trackingUrl = $rule->logistics->url;
                $is_auto = ($rule->logistics->docking == 'MANUAL' ? '0' : '1');
                return $this->update([
                    'status' => 'ASSIGNED',
                    'logistics_id' => $rule->logistics->id,
                    'tracking_link' => $trackingUrl,
                    'logistics_assigned_at' => date('Y-m-d H:i:s'),
                    'is_auto' => $is_auto,
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
            if (!LogisticsModel::where(['name' => $content['logistics_id']])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_logistics = LogisticsModel::where(['name' => $content['logistics_id']])->first();
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
        if (count($arr)) {
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
                            '物流方式' => LogisticsModel::find($key2)->name,
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
        } else {
            $rows[] = [
                '供货商' => '',
                '物流方式' => '',
                '发货日期' => '',
                '运单号' => '',
                '重量' => '',
            ];
        }
        $name = '发货复查';
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '发货复查';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');

    }

    public function scopeOfTrackingNo($query, $trackingNo)
    {
        return $query->where('tracking_no', $trackingNo);
    }

    public function getStatusTextAttribute()
    {
        return config('package.' . $this->status);
    }

    public function shipping()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }
}
