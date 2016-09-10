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

class PickReportController extends Controller
{
    public function __construct(PickReportModel $pickReport)
    {
        $this->model = $pickReport;
        $this->mainIndex = route('pickReport.index');
        $this->mainTitle = '拣货排行榜';
        $this->viewPath = 'pick.report.';
    }

    public function createData()
    {
        $model = PickListModel::wherebetween('pick_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))])->get()->groupBy('pick_by');
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
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')))
                    })->sum('accouont'),
                    'today_picklist' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')))
                    })->count(),
                    'day_time' => date('Y-m-d H:i:s', time()),
                ])
        }
        var_dump($model->toArray());exit;
    }
}