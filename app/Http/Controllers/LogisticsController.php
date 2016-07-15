<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\EmailTemplateModel;
use App\Models\Logistics\LimitsModel;
use App\Models\Logistics\TemplateModel;
use App\Models\LogisticsModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\SupplierModel;
use App\Models\ChannelModel;
use App\Models\Logistics\ChannelNameModel;


class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $logistics)
    {
        $this->model = $logistics;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流';
        $this->viewPath = 'logistics.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'amazons' => ChannelModel::where('name', 'Amazon')->first()->logisticsChannelName,
            'wishes' => ChannelModel::where('name', 'Wish')->first()->logisticsChannelName,
            'aliExpresses' => ChannelModel::where('name', 'AliExpress')->first()->logisticsChannelName,
            'lazadas' => ChannelModel::where('name', 'Lazada')->first()->logisticsChannelName,
            'ebays' => ChannelModel::where('name', 'Ebay')->first()->logisticsChannelName,
            'dhgates' => ChannelModel::where('name', 'Dhgate')->first()->logisticsChannelName,
            'cdiscounts' => ChannelModel::where('name', 'Cdiscount')->first()->logisticsChannelName,
            'jooms' => ChannelModel::where('name', 'Joom')->first()->logisticsChannelName,
            'channels' => ChannelModel::all(),
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
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->create(request()->all());
        if(request()->has('logistics_limits')) {
            $str = implode(',', request('logistics_limits'));
        }
        $model->update(['limit' => $str]);
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            $arr = explode(',', $value);
            $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
            if(!$channelName) {
                continue;
            }
            $buf[$key] = $channelName->id;
        }
        $model->channelName()->sync($buf);
        return redirect($this->mainIndex);
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
            'channelNames' => $model->channelName,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $logistics = $this->model->find($id);
        $limits = explode(",",$logistics->limit);
        $selectedLimits = LimitsModel::whereIn('id', $limits)->get();
        if (!$logistics) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $logistics,
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'selectedLimits' => $selectedLimits,
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'amazons' => ChannelModel::where('name', 'Amazon')->first()->logisticsChannelName,
            'wishes' => ChannelModel::where('name', 'Wish')->first()->logisticsChannelName,
            'aliExpresses' => ChannelModel::where('name', 'AliExpress')->first()->logisticsChannelName,
            'lazadas' => ChannelModel::where('name', 'Lazada')->first()->logisticsChannelName,
            'ebays' => ChannelModel::where('name', 'Ebay')->first()->logisticsChannelName,
            'dhgates' => ChannelModel::where('name', 'Dhgate')->first()->logisticsChannelName,
            'cdiscounts' => ChannelModel::where('name', 'Cdiscount')->first()->logisticsChannelName,
            'jooms' => ChannelModel::where('name', 'Joom')->first()->logisticsChannelName,
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
        $this->validate(request(), $this->model->rules('update', $id));
        $buf = request()->all();
        if(request()->has('logistics_limits')) {
            $buf['limit'] = implode(',', request('logistics_limits'));
        }
        $model->update($buf);
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            $arr = explode(',', $value);
            $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
            if(!$channelName) {
                continue;
            }
            $buf[] = $channelName->id;
        }
        $model->channelName()->sync($buf);
        return redirect($this->mainIndex);
    }

    /**
     * 更新号码池数量
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $array = CodeModel::distinct()->get(['logistics_id']);
        foreach($array as $key => $value)
        {
            $all = CodeModel::where(['logistics_id' => $value['logistics_id']])->count();
            $used = CodeModel::where(['logistics_id' => $value['logistics_id'], 'status' => '1'])->count();
            $unused = $all - $used;
            $pool_quantity = $unused."/".$used."/".$all;
            $arr = LogisticsModel::where(['id' => $value['logistics_id']])->get()->toArray();
            if(count($arr)) {
                foreach($arr as $k => $val)
                {
                    $model = $this->model->find($val['id']);
                    $val['pool_quantity'] = $pool_quantity;
                    $model->update(['pool_quantity' => $val['pool_quantity']]);
                }
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function getLogistics()
    {
        $logistics_id = request('logistics');
        $logistics = $this->model->find($logistics_id);
        if(!$logistics) {
            return json_encode(false);
        }
        $str = "<option class='logis' value='".$logistics->id."'>".$logistics->code."</option>";
        return $str;
    }

}