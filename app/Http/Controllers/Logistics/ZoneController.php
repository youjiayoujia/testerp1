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
use DB;
use DebugBar\JavascriptRenderer;

class ZoneController extends Controller
{
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
//        foreach($this->getCountries() as $a){
//            var_dump($a);
//        }
//        exit;
        return view($this->viewPath . 'create', $response);
    }

    public function getLogisticses()
    {
        return LogisticsModel::all();
    }

    public function getCountries()
    {
        return DB::table('countries')->select('id', 'name')->orderBy('abbreviation', 'asc')->get();
    }

    public function countExpress($id, LogisticsModel $logistics, CountryModel $country)
    {
        $obj = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $obj,
            'logistics' => $logistics->all(),
            'country' => $country->all(),
        ];
        return view('logistics.zone.countExpress', $response);
    }

    public function countPacket($id, LogisticsModel $logistics, CountryModel $country)
    {
        $obj = $this->model->find($id);
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
        $buf = $this->model->find($id)->shipping_id;
        echo json_encode($buf);
    }

    public function country(CountryModel $country)
    {
        $id = $_GET['id'];
        $buf = $country->find($id)->name;
        echo json_encode($buf);
    }

}