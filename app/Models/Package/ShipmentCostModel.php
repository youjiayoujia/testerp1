<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ShipmentCostModel extends BaseModel
{
    protected $table = 'shipment_costs';

    protected $fillable = [
    	'shipmentCostNum',
    	'all_weight',
    	'theory_weight',
    	'all_shipment_cost',
    	'theory_shipment_cost',
    	'average_price',
    	'import_by',
    	'created_at'
    ];

    public function importProcess($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $path = $path . 'excelProcess.xls';
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $buf = [];
        foreach ($arr as $key => $value) {
        	if(!$key) {
        		continue;
        	}
        	foreach($value as $k => $v) {
        		$k1 = iconv('utf-8', 'gb2312', $arr[0][$k]);
	        	$val = iconv('utf-8', 'gb2312', $v);
	            $buf[$key-1][$k1] = $v;
        	}
        }

        return $buf;
    }
}