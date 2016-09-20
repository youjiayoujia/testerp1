<?php
/**
 * 库存结转控制器
 * 处理库存结转相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 16/4/12
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\CarryOverModel;
use App\Models\StockModel;
use App\Models\Stock\InOutModel;
use App\Jobs\StockCarrying;

class CarryOverController extends Controller
{
    public function __construct(CarryOverModel $stockCarryOver)
    {
        $this->model = $stockCarryOver;
        $this->mainIndex = route('stockCarryOver.index');
        $this->mainTitle = '库存结转';
        $this->viewPath = 'stock.carryOver.';
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
            'forms' => $model->forms()->paginate('1000'),
        ];
        return view($this->viewPath . 'show', $response);
    }

    public function createCarryOver(){
        $response = [
            'metas' => $this->metas(__FUNCTION__, '月结'),
        ];

        return view($this->viewPath.'month', $response);
    }

    public function CreateCarryOverResult()
    {
        $tmp_timestamp = strtotime(request('stockTime'));
        $jobs = new StockCarrying($tmp_timestamp);
        $jobs->onQueue('stockCarrying');
        $this->dispatch($jobs);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '库存结转成功加入队列'));
    }

    public function showStock()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '查看库存'),
        ];

        return view($this->viewPath.'showStock', $response);
    }

    public function showStockView()
    {
        $stockTime = request('stockTime');
        $tmp = date('Y-m', strtotime($stockTime));
        $objCarryOver = $this->model->where('date', '<=', $tmp)->orderBy('date', 'desc')->first();
        if(!$objCarryOver) {
            throw new Exception('该时间段没有库存');
        }
        $carryOverTime = date('Y-m-d G:i:s', (strtotime($objCarryOver->date)+(strtotime('+1 month')-strtotime('now'))));
        $len = 1000;
        $start = 0;
        $carryOverForms = $objCarryOver->forms()->skip($start)->take($len)->get();
        while($carryOverForms->count()) {
            foreach($carryOverForms as $carryOverForm) {
                $stockIns = InOutModel::where('stock_id', $carryOverForm->stock_id)->where('outer_type', 'IN')->whereBetween('created_at', [$carryOverTime, $stockTime])->get();
                foreach($stockIns as $stockIn)
                {
                    $carryOverForm->over_quantity += $stockIn->quantity;
                    $carryOverForm->over_amount += $stockIn->amount;
                }    
                $stockOuts = InOutModel::where('stock_id', $carryOverForm->stock_id)->where('outer_type', 'OUT')->whereBetween('created_at', [$carryOverTime, $stockTime])->get();
                foreach($stockOuts as $stockOut)
                {
                    $carryOverForm->over_quantity -= $stockOut->quantity;
                    $carryOverForm->over_amount -= $stockOut->amount;
                }  
            }
            $start += $len;
            $carryOverForms = $objCarryOver->forms()->skip($start)->take($len)->get();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'carryOvers' => $carryOverForms()->paginate('1000'),
            'stockTime' => $stockTime,
        ];

        return view($this->viewPath.'showStockView', $response);
    }
}