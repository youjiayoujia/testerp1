<?php
/**
 * 物流分配规则控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午3:20
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\CountriesModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\TransportModel;
use App\Models\LogisticsModel;
use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Logistics\LimitsModel;
use App\Models\CountriesSortModel;
use App\Models\UserModel;

class RuleController extends Controller
{
    public function __construct(RuleModel $rule)
    {
        $this->model = $rule;
        $this->mainIndex = route('logisticsRule.index');
        $this->mainTitle = '物流分配规则';
        $this->viewPath = 'logistics.rule.';
    }

    /**
     * 新增
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses' => LogisticsModel::all(),
            'catalogs' => CatalogModel::all(),
            'countrySorts' => CountriesSortModel::all(),
            'channels' => ChannelModel::all(), 
            'logisticsLimits' => LimitsModel::all(),
            'accounts' => AccountModel::all(),
            'transports' => TransportModel::all(),
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
        $model->createAll(request()->all());
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
            'countries' => $model->rule_countries_through,
            'channels' => $model->rule_channels_through,
            'catalogs' => $model->rule_catalogs_through,
            'limits' => $model->rule_limits_through,
            'accounts' => $model->rule_accounts_through,
            'transports' => $model->rule_transports_through,
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
        $model = $this->model->find($id);
        $selectedCountry = explode(",",$model->country);
        $selectedCountries = CountriesModel::whereIn('code', $selectedCountry)->get();
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
            'catalogs_outer' => CatalogModel::all(),
            'countrySorts' => CountriesSortModel::all(),
            'channels_outer' => ChannelModel::all(), 
            'logisticsLimits_outer' => LimitsModel::all(),
            'accounts_outer' => AccountModel::all(),
            'transports_outer' => TransportModel::all(),
            'countries' => $model->rule_countries_through,
            'channels' => $model->rule_channels_through,
            'catalogs' => $model->rule_catalogs_through,
            'limits' => $model->rule_limits_through,
            'accounts' => $model->rule_accounts_through,
            'transports' => $model->rule_transports_through,
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
        $model->updateAll(request()->all());
        $model = $this->model->with('rule_transports')->with('rule_limits')->with('rule_countries')->with('rule_accounts')->with('rule_channels')->with('rule_catalogs')->find($id);
        $to = base64_encode(serialize($model));
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);
        return redirect($this->mainIndex);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->rule_limits_through()->sync([]);
        $model->rule_channels_through()->sync([]);
        $model->rule_catalogs_through()->sync([]);
        $model->rule_countries_through()->sync([]);
        $model->rule_accounts_through()->sync([]);
        $model->rule_transports_through()->sync([]);
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}