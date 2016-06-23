<?php
namespace App\Models;

use Excel;
use App\Base\BaseModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\SupplierModel;
use App\Models\Logistics\ZoneModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['tracking_no'];

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
            'relatedSearchFields' => ['warehouse' => ['name'], 'channel' => ['name'], 'channelAccount' => ['account'], 'logistics' => ['short_code', 'logistics_type']],
            'filterFields' => ['tracking_no'],
            'filterSelects' => ['status' => config('package')],
            'selectRelatedSearchs' => [
                'order' => ['status' => config('order.status'), 'active' => config('order.active')],
            ],
            'sectionSelect' => ['time' => ['created_at']],
        ];
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
                    foreach($countries as $country) {
                        if($country->code == $this->shipping_country) {
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
                    $limits = $this->rule_limits_through;
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
                if(!$warehouse->logisticsIn($rule->type_id)) {
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

    /**
     * 计算利润率并处理
     *
     * @param none
     * @return 利润率 小数
     *
     */
    public function calculateProfitProcess()
    {
        $order = $this->order;
        $orderItems = $order->items;
        $orderAmount = $order->amount;
        $orderCosting = $order->all_item_cost;
        $orderChannelFee = $this->calculateOrderChannelFee($order, $orderItems);
        $orderRate = ($order->amount - ($orderCosting + $this->calculateOrderChannelFee($order,
                        $orderItems) + $order->logistics_fee)) / $order->amount;
        if ($orderRate <= 0) {
            //利润率为负撤销
            $this->OrderCancle($order, $orderItems);
        }

        return $orderRate;
    }

    /**
     *  计算平台费
     *
     * @param $order 订单 $orderItems 订单条目
     * @return $sum
     *
     */
    public function calculateOrderChannelFee($order, $orderItems)
    {
        $sum = 0;
        $channel = $order->channel;
        if ($channel->flat_rate == 'channel' && $channel->rate == 'channel') {
            return ($order->amount + $order->logistics_fee) * $channel->rate_value + $channel->flat_rate_value;
        }
        if ($channel->flat_rate == 'channel' && $channel->rate == 'catalog') {
            $sum += $channel->flat_rate_value;
            foreach ($orderItems as $orderItem) {
                $rate = $orderItem->item->catalog->channels->first()->pivot->rate;
                $tmp = ($orderItem->price * $orderItem->quantity + ($orderItem->quantity / $order->order_quantity) * $order->logistics_fee) * $rate;
                $sum += $tmp;
            }
            return $sum;
        }
        if ($channel->flat_rate == 'catalog' && $channel->rate == 'channel') {
            $sum = ($order->amount + $order->logistics_fee) * $channel->rate_value;
            foreach ($orderItems as $orderItem) {
                $flat_rate_value = $orderItem->item->catalog->channels->first()->pivot->flat_rate_value;
                $sum += $flat_rate_value;
            }
            return $sum;
        }
        if ($channel->flat_rate == 'catalog' && $channel->rate == 'catalog') {
            foreach ($orderItems as $orderItem) {
                $buf = $orderItem->item->catalog->channels->first()->pivot;
                $flat_rate_value = $buf->flat_rate_value;
                $rate_value = $buf->rate_value;
                $sum += ($orderItem->price * $orderItem->quantity + ($orderItem->quantity / $order->order_quantity) * $order->logistics_fee) * $rate_value + $flat_rate_value;
            }
            return $sum;
        }
    }

    /**
     * 订单取消
     *
     * @param $order 订单 $orderItems 订单条目
     * @return none
     *
     */
    public function OrderCancle($order, $orderItems)
    {
        $order->update(['status' => 'CANCLE']);
        foreach ($orderItems as $orderItem) {
            $orderItem->update(['is_active' => '0']);
        }
        $packages = $order->packages;
        foreach ($packages as $package) {
            $package->update(['status' => 'CANCLE']);
            foreach ($package->items as $packageItem) {
                $item = $packageItem->item;
                $item->in($packageItem->warehouse_position_id, $packageItem->quantity,
                    $packageItem->quantity * $item->cost, 'PACKAGE_CANCLE', '',
                    ('订单号:' . $order->ordernum . ' 包裹号:' . $package->id));
            }
        }
    }

    public function scopeOfTrackingNo($query, $trackingNo)
    {
        $trackingNo = 2131253151;
        return $query->where('tracking_no', $trackingNo);
    }
}
