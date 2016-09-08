<?php
/**
 * 入库控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\InOutModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\UserModel;
use Excel;

class InOutController extends Controller
{
    public function __construct(InOutModel $inout)
    {
        $this->model = $inout;
        $this->mainIndex = route('stockInOut.index');
        $this->mainTitle = '出入库';
        $this->viewPath = 'stock.inout.';
    }

    public function export()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'types' => config('inout.ALL_TYPE'),
            'users' => UserModel::all(),
        ];

        return view($this->viewPath.'export', $response);
    }

    public function exportResult()
    {
        $query = $this->model;
        if(request()->has('start_time') && request()->has('end_time')) {
            $start_time = request('start_time');
            $end_time = request('end_time');
            $query = $query->whereBetween('created_at', [$start_time, $end_time]);
        }
        $arr = [];
        if(request()->has('types')) {
            $types = request('types');
            foreach($types as $type) {
                $buf = explode('.', $type);
                $arr[$buf[0]][] = $buf[1];
            }
            $flag = 1;
            foreach($arr as $key => $value) {
                if($flag) {
                    $query = $query->where('outer_type', $key)->whereIn('inner_type', $value);
                } else {
                    $query = $query->orWhere(function($query) use ($key, $value){
                        $query = $query->where('outer_type', $key)->whereIn('inner_type', $value);
                    });
                }
                $flag = 0;
            }
        }
        if(request()->has('item_id')) {
            $sku = request('item_id');
            $query = $query->whereHas('stock', function($query) use ($sku){
                $query = $query->whereHas('item', function($query) use ($sku){
                    $query->where('sku', 'like', '%'.$sku.'%');
                });
            });
        }
        $rows = [];
        $start = 0;
        $len = 100;
        $inouts = $query->skip($start)->take($len)->get();
        while($inouts->count()) {
            foreach($inouts as $key => $inout) {
                $item = $inout->stock->item;
                $rows[] = [
                    '单据号' => $inout->relation_name,
                    '产品中文名' => $item->c_name,
                    'sku' => $item->sku,
                    '数量' => $inout->quantity,
                    '采购单价' => $item->purchase_cost,
                    '供应商编号' => $item->supplier_id,
                    '时间' => $inout->created_at,
                    '备注' => $inout->remark,
                    '类型' => $inout->type_name,
                ];
            }
            $start += $len;
            unset($inouts);
            $inouts = $query->skip($start)->take($len)->get();
        }
        $name = '出入库数据导出';
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}