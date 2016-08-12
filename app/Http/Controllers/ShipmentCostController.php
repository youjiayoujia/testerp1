<?php
/**
 * 物流对账控制器
 * 处理物流对账相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package\ShipmentCostModel;
use Excel;

class ShipmentCostController extends Controller
{
    public function __construct(ShipmentCostModel $shipmentCost)
    {
        $this->model = $shipmentCost;
        $this->mainIndex = route('shipmentCost.index');
        $this->mainTitle = '物流对账';
        $this->viewPath = 'package.shipmentCost.';
    }

    public function export()
    {
    	$rows[] = [
    		'挂号码' => 'LN108905230CN',
    		'目的地' => '美国',
    		'计费重量(kg)' => '0.237',
    		'渠道名称' => 'JSCS_EUB',
    		'不含挂号费' => '18.96',
    		'挂号费' => '7',
    		'通折' => '0.83',
    		'非通折' => '',
    	];
    	$name = '物流对账模板';
    	Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function import()
    {
    	$response = [
    		'metas' => $this->metas(__FUNCTION__),
    	];

    	return view($this->viewPath.'import', $response);
    }

    public function importProcess()
    {
    	$file = request()->file('import');
        $arr = $this->model->importProcess($file);
        $errors = [];
       	var_dump($arr[0]);exit;
    }
}