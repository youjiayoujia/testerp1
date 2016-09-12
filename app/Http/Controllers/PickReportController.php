<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\PickReportModel;
use App\Models\PickListModel;
use App\Models\WarehouseModel;

class PickReportController extends Controller
{
    public function __construct(PickReportModel $pickReport)
    {
        $this->model = $pickReport;
        $this->mainIndex = route('pickReport.index');
        $this->mainTitle = '拣货排行榜';
        $this->viewPath = 'pick.report.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $model = $this->model->orderBy('day_time', 'desc')->first();
        $monthModel = '';
        if($model) {
            $last_time = $this->model->orderBy('day_time', 'desc')->first()->day_time;
            $model = $this->model->orderBy('day_time', 'desc')->get()->groupBy('day_time')->get($last_time);
            $monthModel = $this->model->whereBetween('day_time',[date('Y-m', strtotime('now')), date('Y-m', strtotime('+1 month'))])->get();
            $flag = 0;
            if(request()->has('date') && !empty(request('date'))) {
                $flag = 1;
                $model = $this->model->whereBetween('day_time', [date('Y-m-d', strtotime(request('date'))), date('Y-m-d', strtotime(request('date')) + strtotime('+1 day') - strtotime('now'))]);
            }
            if(request()->has('warehouseid') && !empty(request('warehouseid'))) {
                $flag = 1;
                $model = $model->where('warehouse_id', request('warehouseid'));
            }
            if($flag) {
                $model = $model->orderBy('day_time', 'desc')->get()->groupBy('day_time')->get($last_time);
            }
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $model,
            'mixedSearchFields' => $this->model->mixed_search,
            'monthModel' => $monthModel,
            'warehouses' => WarehouseModel::all(),
        ];

        return view($this->viewPath . 'index', $response);
    }

    public function createData()
    {
        $model = PickListModel::wherebetween('pick_at', [date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day'))])->get()->groupBy('pick_by');
        foreach($model as $userId => $block) {
            $this->model->create([
                    'user_id' => $userId,
                    'single' => $block->filter(function($single){
                        return $single->type == 'SINGLE';
                    })->sum('account'),
                    'singleMulti' => $block->filter(function($single){
                        return $single->type == 'SINGLEMULTI';
                    })->sum('account'),
                    'multi' => $block->filter(function($single){
                        return $single->type == 'MULTI';
                    })->sum('account'),
                    'today_pick' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')));
                    })->sum('accouont'),
                    'today_picklist' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')));
                    })->count(),
                    'day_time' => date('Y-m-d H:i:s', time()),
                ]);
        }
        
        return redirect($this->mainIndex);
    }
}