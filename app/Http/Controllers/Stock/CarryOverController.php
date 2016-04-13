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

    public function ajaxCreateCarryOver()
    {
        $stocks = StockModel::all();
        foreach($stocks as $stock)
        {
            $carryOver = CarryOverModel::where('stock_id', $stock->id)->orderBy('carry_over_time', 'desc')->first();
            $begin_quantity = 0;
            $begin_amount = 0;
            if($carryOver) {
                $begin_quantity = $carryOver->over_quantity;
                $begin_amount = $carryOver->over_amount;
            }
            $this->model->create([
                    'stock_id' => $stock->id,
                    'begin_quantity' => $begin_quantity,
                    'begin_amount' => $begin_amount,
                    'over_quantity' => $stock->all_quantity,
                    'over_amount' => $stock->amount,
                    'carry_over_time' => date('Y-m-d h:i:s', time()),
                ]);
        }

        return json_encode(true);
    }

    public function showStock()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'showStock', $response);
    }

    public function showStockView()
    {
        $stockTime = request('stockTime');
        $carryOverTime = '';
        $carryOver = CarryOverModel::groupBy('carry_over_time')->where('carry_over_time', '<', $stockTime)->orderBy('carry_over_time', 'desc')->first();
        if($carryOver) {
            $carryOverTime = $carryOver->carry_over_time;
        } else {
            throw new Exception('该时间段没有库存');
        }
        $carryOvers = CarryOverModel::where('carry_over_time', $carryOverTime)->get();
        $stockIns = InModel::whereBetween('created_at', [$carryOverTime, $stockTime])->get();
        $stockOuts = OutModel::whereBetween('created_at', [$carryOverTime, $stockTime])->get();
        if(count($stockIns)) 
        {
            foreach($stockIns as $stockIn)
            {
                foreach($carryOvers as $carryOver)
                {
                    if($carryOver->stock_id == $stockIn->stock_id) {
                        $carryOver->over_quantity += $stockIn->quantity;
                        $carryOver->over_amount += $stockIn->amount;
                        break;
                    }
                }
            }
        }
        if(count($stockOuts)) 
        {
            foreach($stockOuts as $stockOut)
            {
                foreach($carryOvers as $carryOver)
                {
                    if($carryOver->stock_id == $stockOut->stock_id) {
                        $carryOver->over_quantity -= $stockOut->quantity;
                        $carryOver->over_amount -= $stockOut->amount;
                        break;
                    }
                }
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'carryOvers' => $carryOvers,
            'stockTime' => $stockTime,
        ];

        return view($this->viewPath.'showStockView', $response);

    }
}