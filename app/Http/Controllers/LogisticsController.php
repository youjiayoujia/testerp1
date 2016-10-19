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
use App\Models\UserModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\SupplierModel;
use App\Models\ChannelModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Logistics\ChannelModel as logisticsChannel;

class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $logistics)
    {
        $this->model = $logistics;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流方式';
        $this->viewPath = 'logistics.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $arr = [];
        $channels = ChannelModel::all();
        foreach($channels as $channel) {
            $arr[$channel->name] = $channel->logisticsChannelName;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'arr' => $arr,
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
        foreach(request('url') as $k => $v) {
            $data['channel_id'] = $k;
            $data['url'] = $v;
            $data['is_up'] = request('channel_id')[$k];
            $model->logisticsChannels()->create($data);
        }
        $str = '';
        if(request()->has('logistics_limits')) {
            $str = implode(',', request('logistics_limits'));
            $model->update(['limit' => $str]);
        }
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            if(!empty($value)) {
                $arr = explode(',', $value);
                $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
                if(!$channelName) {
                    continue;
                }
                $buf[$key] = $channelName->id;
            } else {
                if(!empty(request($key.'_name'))) {
                    $channelName = ChannelNameModel::create(['channel_id' => request($key.'_channelId'), 'name' => request($key.'_name')]);
                    $buf[$key] = $channelName->id;
                }
            }
        }
        $model->channelName()->sync($buf);
        $model = $this->model->with('logisticsChannels')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', base64_encode(serialize($model)));
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
            'logisticsChannels' => $model->logisticsChannels,
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
        $arr = [];
        $channels = ChannelModel::all();
        foreach($channels as $channel) {
            $arr[$channel->name] = $channel->logisticsChannelName;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $logistics,
            'logisticsChannels' => $logistics->logisticsChannels,
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'selectedLimits' => $selectedLimits,
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'arr' => $arr,
            'channels' => ChannelModel::all(),
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
        $userName = UserModel::find(request()->user()->id);
        $from = base64_encode(serialize($model));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $buf = request()->all();
        $buf['limit'] = '';
        if(request()->has('logistics_limits')) {
            $buf['limit'] = implode(',', request('logistics_limits'));
        }
        $model->update($buf);
        foreach(request('url') as $k => $v) {
            $logisticsChannels = logisticsChannel::where(['logistics_id' => $id, 'channel_id' => $k]);
            if($logisticsChannels->count() > 0) {
                if($logisticsChannels->first()->url != $v) {
                    $logisticsChannels->update(['url' => $v]);
                }
                if($logisticsChannels->first()->is_up != request('channel_id')[$k]) {
                    $logisticsChannels->update(['is_up' => request('channel_id')[$k]]);
                }
            }else {
                $data['logistics_id'] = $id;
                $data['channel_id'] = $k;
                $data['url'] = $v;
                $data['is_up'] = request('channel_id')[$k];
                logisticsChannel::create($data);
            }
        }
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            if(!empty(request($key.'_name'))) {
                $channelName = ChannelNameModel::create(['channel_id' => request($key.'_channelId'), 'name' => request($key.'_name')]);
                $buf[$key] = $channelName->id;
            } else {
                if(!empty($value)) {
                    $arr = explode(',', $value);
                    $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
                    if(!$channelName) {
                        continue;
                    }
                    $buf[$key] = $channelName->id;
                }
            }
        }
        $model->channelName()->sync($buf);
        $model = $this->model->with('logisticsChannels')->find($id);
        $to = base64_encode(serialize($model));
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);
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

    /**
     * 获取物流商信息
     */
    public function ajaxSupplier()
    {
        if (request()->ajax()) {
            $supplier = trim(request()->input('logistics_supplier_id'));
            $buf = SupplierModel::where('name', 'like', '%' . $supplier . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

}