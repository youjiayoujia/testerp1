<?php
/**
 * 物流分区控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:46
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\CountriesModel;
use App\Models\Logistics\ZoneModel;
use App\Models\LogisticsModel;

class ZoneController extends Controller
{
    public function __construct(ZoneModel $zoneModel)
    {
        $this->model = $zoneModel;
        $this->mainIndex = route('logisticsZone.index');
        $this->mainTitle = '物流分区';
        $this->viewPath = 'logistics.zone.';
    }

    /**
     * 新增
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses'=>LogisticsModel::all(),
            'countries'=>CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $selectedCountry = explode(",", $model->country_id);
        $selectedCountries = CountriesModel::whereIn('code', $selectedCountry)->get();
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'countries' => CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
            'selectedCountries' => $selectedCountries,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 快递运费计算
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countExpress($id)
    {
        $zone = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $zone,
            'logistics' => LogisticsModel::all(),
            'country' => CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
        ];
        return view('logistics.zone.countExpress', $response);
    }

    /**
     * 小包运费计算
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countPacket($id)
    {
        $zone = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $zone,
            'logistics' => LogisticsModel::all(),
            'country' => CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
        ];
        return view('logistics.zone.countPacket', $response);
    }

    /**
     * ajax获取快递种类
     */
    public function zoneShipping()
    {
        $id = request()->input("id");
        $buf = $this->model->find($id)->shipping_id;
        return json_encode($buf);
    }

}