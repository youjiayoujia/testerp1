<?php
/**
 * 库存结转控制器
 * 处理库存结转相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
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
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;

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
            'forms' => $model->forms,
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
        $carryOver = $this->model->orderBy('date', 'desc')->first();
        if($carryOver) {
            $latest = strtotime($carryOver->date);
            if($latest >= $tmp_timestamp) {
                throw new Exception('日期有误，该日期可能已经月结过');
            }
            $below40Days = (strtotime('now') - strtotime('-40 day'));
            if(($tmp_timestamp - $below40Days) > $latest) {
                throw new Exception('日期有误，可能上个月月结没做');
            }
            $carryOverNewObj = $this->model->create([
                    'date' => date('Y-m', $tmp_timestamp),
                ]);
            DB::beginTransaction();
            try {
                $carryOverForms = $carryOver->forms;
                foreach($carryOverForms as $carryOverForm) {
                    $carryOverNewObj->forms()->create(['stock_id'=>$carryOverForm->stock_id, 
                                                    'begin_quantity' => $carryOverForm->over_quantity,
                                                    'begin_amount' => $carryOverForm->over_amount]);
                }
                $stockIns = InModel::whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $tmp_timestamp)])->get();
                $stockOuts = OutModel::whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $tmp_timestamp)])->get();
                if(count($stockIns)) 
                {
                    foreach($stockIns as $stockIn)
                    {
                        foreach($carryOverForms as $carryOverForm)
                        {
                            if($carryOverForm->stock_id == $stockIn->stock_id) {
                                $carryOverForm->over_quantity += $stockIn->quantity;
                                $carryOverForm->over_amount += $stockIn->amount;
                                break;
                            }
                        }
                    }
                }
                if(count($stockOuts)) 
                {
                    foreach($stockOuts as $stockOut)
                    {
                        foreach($carryOverForms as $carryOverForm)
                        {
                            if($carryOverForm->stock_id == $stockOut->stock_id) {
                                $carryOverForm->over_quantity -= $stockOut->quantity;
                                $carryOverForm->over_amount -= $stockOut->amount;
                                break;
                            }
                        }
                    }
                }
                foreach($carryOverForms as $carryOverForm) {
                    $carryOverNewObj->forms->where('stock_id', $carryOverForm->stock_id)->first()->update([
                                                                        'over_quantity' => $carryOverForm->over_quantity,
                                                                        'over_amount' => $carryOverForm->over_amount]);
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();

        } else {
            DB::beginTransaction();
            try {
                $carryOverNewObj = $this->model->create([
                        'date' => date('Y-m', $tmp_timestamp),
                    ]);
                $stocks = StockModel::all();
                foreach($stocks as $stock)
                {
                    $carryOverNewObj->forms()->create([
                            'stock_id' => $stock->id,
                            'over_quantity' => $stock->all_quantity,
                            'over_amount' => $stock->all_quantity * $stock->unit_cost,
                        ]);
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        }

        return redirect($this->mainIndex);
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
        $carryOverForms = $objCarryOver->forms;
        $stockIns = InModel::whereBetween('created_at', [$carryOverTime, $stockTime])->get();
        $stockOuts = OutModel::whereBetween('created_at', [$carryOverTime, $stockTime])->get();
        if(count($stockIns)) 
        {
            foreach($stockIns as $stockIn)
            {
                foreach($carryOverForms as $carryOverForm)
                {
                    if($carryOverForm->stock_id == $stockIn->stock_id) {
                        $carryOverForm->over_quantity += $stockIn->quantity;
                        $carryOverForm->over_amount += $stockIn->amount;
                        break;
                    }
                }
            }
        }
        if(count($stockOuts)) 
        {
            foreach($stockOuts as $stockOut)
            {
                foreach($carryOverForms as $carryOverForm)
                {
                    if($carryOverForm->stock_id == $stockOut->stock_id) {
                        $carryOverForm->over_quantity -= $stockOut->quantity;
                        $carryOverForm->over_amount -= $stockOut->amount;
                        break;
                    }
                }
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'carryOvers' => $carryOverForms,
            'stockTime' => $stockTime,
        ];

        return view($this->viewPath.'showStockView', $response);

    }
}