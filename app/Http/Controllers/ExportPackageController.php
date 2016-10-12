<?php
/**
 * 数据模板控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package\ExportModel;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\WarehouseModel;
use App\Models\PackageModel;
use Excel;

class ExportPackageController extends Controller
{
    public function __construct(ExportModel $export)
    {
        $this->model = $export;
        $this->mainIndex = route('exportPackage.index');
        $this->mainTitle = '模版';
        $this->viewPath = 'package.export.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'fields' => config('exportPackage'),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $model = $this->model->create(request()->all());
        $fieldNames = request('fieldNames');
        foreach($fieldNames as $fieldName) {
            $level = request($fieldName.',level') ? request($fieldName.',level') : 'Z';
            $model->items()->create(['name' => $fieldName, 'level' => $level]);
        }
        if(request()->has('arr')) {
            $arr = request('arr');
            foreach($arr['fieldName'] as $key => $value) {
                if($value) {
                    $model->extra()->create(['fieldName' => $arr['fieldName'][$key], 'fieldValue' => $arr['fieldValue'][$key], 'fieldLevel' => $arr['fieldLevel'][$key]]);
                }
            }
        }

        return redirect($this->mainIndex);
    }

    public function extraField()
    {
        $current = request('current');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'current' => $current,
        ];

        return view($this->viewPath.'extraField', $response);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'exportPackageItems' => $model->items,
            'arr' => config('exportPackage'),
            'extras' => $model->extra,
        ];

        return view($this->viewPath . 'show', $response);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'fields' => config('exportPackage'),
            'items' => $model->items,
            'extras' => $model->extra
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $model->update(request()->all());
        $items = $model->items;
        $arr_items = request('fieldNames');
        if($items->count() >= count($arr_items)) {
            foreach($items as $key => $value) {
                if(array_key_exists($key, $arr_items)) {
                    $level = request($arr_items[$key].",level") ? request($arr_items[$key].",level") : 'z';
                    $value->update(['name' => $arr_items[$key], 'level' => $level]);
                } else {
                    $value->delete();
                }
            }
        } else {
            foreach($items as $key => $value) {
                $level = request($arr_items[$key].",level") ? request($arr_items[$key].",level") : 'z';
                $value->update(['name' => $arr_items[$key], 'level' => $level]);
            }
            for($i = $items->count(); $i < count($arr_items); $i++) {
                $level = request($arr_items[$i].",level") ? request($arr_items[$i].",level") : 'z';
                $model->items()->create(['name' => $arr_items[$i], 'level' => $level]);
            }
        }
        $extras = $model->extra;
        foreach($extras as $extra) {
            $extra->delete();
        }
        if(request()->has('arr')) {
            $arr = request('arr');
            foreach($arr['fieldName'] as $key => $value) {
                if($value) {
                    $model->extra()->create(['fieldName' => $arr['fieldName'][$key], 'fieldValue' => $arr['fieldValue'][$key], 'fieldLevel' => $arr['fieldLevel'][$key]]);
                }
            }
        }

        return redirect($this->mainIndex);
    }

    public function exportPackageView()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'fields' => $this->model->all(),
            'channels' => ChannelModel::all(),
            'warehouses' => WarehouseModel::all(),
            'statuses' => config('package'),
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath.'exportPackageView', $response);
    }

    /**
     *  导出包裹数据信息 
     *
     *  @param none
     *  @return excel
     *
     */
    public function exportPackageDetail()
    {
        if(!request()->hasFile('accordingTracking')) {
            $field = $this->model->find(request('field_id'));
            $fieldItems = $field->items;
            $arr = [];
            foreach($fieldItems as $fieldItem) {
                $arr[$fieldItem->level] = $fieldItem->name;
            }
            ksort($arr);
            $packages = '';
            if(request()->has('channel_id')) {
                $packages = PackageModel::where('channel_id', request('channel_id'));
            }
            if(request()->has('warehouse_id')) {
                $packages = $packages->where('warehouse_id', request('warehouse_id'));
            }
            if(request()->has('logistics_id')) {
                $packages = $packages->where('logistics_id', request('logistics_id'));
            }
            if(request()->has('status')) {
                $packages = $packages->where('status', request('status'));
            }
            if(request()->has('begin_shipped_at') && request()->has('over_shipped_at')) {
                $packages = $packages->whereRaw('shipped_at >=  ? and shipped_at <= ?', [request('begin_shipped_at'), request('over_shipped_at')]);
            }
            $packages = $packages->get($arr);
            if(!empty($packages)) {
                $buf = config('exportPackage');
                $extras = [];
                foreach($field->extra as $extra) {
                    $extras[$extra->fieldLevel]['name'] = $extra->fieldName;
                    $extras[$extra->fieldLevel]['value'] = $extra->fieldValue;
                }
                ksort($extras);
                $rows = $this->model->calArray($packages, $buf, $arr, $extras);
                $name = 'export_packages';
                Excel::create($name, function($excel) use ($rows){
                    $excel->sheet('', function($sheet) use ($rows){
                        $sheet->fromArray($rows);
                    });
                })->download('csv');
            } else {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '条件给的有问题信息有误'));
            }
            
        } else {
            $file = request()->file('accordingTracking');
            $arr = $this->model->processGoods($file);
            $packageStatus = config('package');
            $errors = [];
            $rows = [];
            foreach($arr as $key => $tracking_no) {
                $model = PackageModel::where('tracking_no', $tracking_no)->first();
                if(!$model) {
                    $errors[$key]['id'] = $tracking_no;
                    $errors[$key]['remark'] = '对应包裹不存在';
                    continue;
                }
                $rows[$key] = [
                    '包裹Id' => $model->id,
                    '渠道' => $model->channel ? $model->channel->name : '',
                    '渠道账号' => $model->channelAccount ? $model->channelAccount->name : '',
                    '订单号' => $model->order ? $model->order->ordernum : '',
                    '仓库' => $model->warehouse ? $model->warehouse->name : '',
                    '物流' => $model->logistics ? $model->logistics->code : '',
                    '类型' => $model->type =='SINGLE' ? '单单' : ($model->type == 'MULTI' ? '多多' : '单多'),
                    '物流成本' => $model->cost + $model->cost1,
                    '重量' => $model->weight,
                    '实际重量' => $model->actual_weight,
                    '追踪号' => $model->tracking_no,
                    '追踪链接' => $model->tracking_link,
                    '是否标记' => $model->is_mark ? '是' : '否',
                    'email' => $model->email,
                    '发货名字' => $model->shipping_firstname . ' '. $model->shipping_lastname,
                    '发货地址' => $model->shipping_address,
                    '发货地址1' => $model->shipping_address1,
                    '发货城市' => $model->shipping_city,
                    '发货省/州' => $model->shipping_state,
                    '发货国家' => $model->shipping_country,
                    '发货邮编' => $model->shipping_zipcode,
                    '发货电话' => $model->shipping_phone,
                    '发货时间' => $model->shipped_at,
                    'status' => $packageStatus[$model->status],
                ];
            }
            $name = 'export_packages_tracking';
            Excel::create($name, function($excel) use ($rows){
                $excel->sheet('', function($sheet) use ($rows){
                    $sheet->fromArray($rows);
                });
            })->download('csv');
        }
        
    }
}