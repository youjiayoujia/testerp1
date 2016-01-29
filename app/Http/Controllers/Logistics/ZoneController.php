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
use App\Models\Logistics\ZoneModel as ZoneModel;
use App\Models\LogisticsModel as LogisticsModel;
use App\Models\CountryModel as CountryModel;

class ZoneController extends Controller
{
    protected $zone;

    public function __construct(ZoneModel $zoneModel)
    {
        $this->model = $zoneModel;
        $this->mainIndex = route('logisticsZone.index');
        $this->mainTitle = '物流';
        $this->viewPath = 'logistics.zone.';
    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses'=>$this->getLogisticses(),
            'countries'=>$this->getCountries(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function getLogisticses()
    {
        return LogisticsModel::all();
    }

    public function getCountries()
    {
        return CountryModel::all();
    }

    public function countExpress($id, LogisticsRepository $logistics, CountryRepository $country)
    {
        $obj = $this->zone->get($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $obj,
            'logistics' => $logistics->all(),
            'country' => $country->all(),
        ];
        return view('logistics.zone.countExpress', $response);
    }

    public function countPacket($id, LogisticsRepository $logistics, CountryRepository $country)
    {
        $obj = $this->zone->get($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $obj,
            'logistics' => $logistics->all(),
            'country' => $country->all(),
        ];
        return view('logistics.zone.countPacket', $response);
    }

    public function zoneShipping()
    {
        $id = $_GET['id'];
        $buf = $this->zone->get($id)->shipping_id;
        echo json_encode($buf);
    }

    public function country(CountryRepository $country)
    {
        $id = $_GET['id'];
        $buf = $country->get($id)->name;
        echo json_encode($buf);
    }

}