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
use App\Models\Logistics\ZoneModel;
use App\Models\LogisticsModel;
use App\Models\CountryModel;
use DB;

class ZoneController extends Controller
{
    public function __construct(ZoneModel $zoneModel)
    {
        $this->model = $zoneModel;
        $this->mainIndex = route('logisticsZone.index');
        $this->mainTitle = '物流';
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
            'countries'=>CountryModel::select('id', 'name')->orderBy('abbreviation', 'asc')->get(),
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
        $selectedCountry = $model->country_id;
        $selectedCountries = explode(",",$selectedCountry);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'countries'=>CountryModel::select('id', 'name')->orderBy('abbreviation', 'asc')->get(),
            'selectedCountries'=>$selectedCountries,
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
        $obj = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $obj,
            'logistics' => LogisticsModel::all(),
            'country' => CountryModel::all(),
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
        $obj = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $obj,
            'logistics' => LogisticsModel::all(),
            'country' => CountryModel::all(),
        ];
        return view('logistics.zone.countPacket', $response);
    }

    /**
     * ajax获取快递种类
     */
    public function zoneShipping()
    {
        $id = $_GET['id'];
        $buf = $this->model->find($id)->shipping_id;
        echo json_encode($buf);
    }

}