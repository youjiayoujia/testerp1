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
use App\Models\CountriesModel;
use App\Models\Logistics\RuleModel;
use App\Models\LogisticsModel;
use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Logistics\LimitsModel;
use App\Models\CountriesSortModel;

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

            'countries' => $model->rule_countries_through,
            'channels' => $model->rule_channels_through,
            'catalogs' => $model->rule_catalogs_through,
            'limits' => $model->rule_limits_through,
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
        $model->updateAll(request()->all());
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
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}