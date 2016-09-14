<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\StockModel;
use App\Models\InOutModel;
use App\Models\Stock\CarryOverModel;
use DB;

class StockCarrying extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $this->time = $time;
        $this->description = 'Stock Taking. '.date('Y-m-d H:i:s', time());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        var_dump('in');
        $carryOver = CarryOverModel::orderBy('date', 'desc')->first();
        if($carryOver) {
            var_dump('has data');
            $latest = strtotime($carryOver->date);
            if($latest >= $this->time) {
                var_dump('date error, maybe is already carryOvered');
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'date error, maybe is already carryOvered';
                $this->log('StockCarrying');
                exit;
            }
            $below40Days = (strtotime('now') - strtotime('-40 day'));
            if(($this->time - $below40Days) > $latest) {
                var_dump('date error, maybe the before month carryOver not done');
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'date error, maybe the before month carryOver not done';
                $this->log('StockCarrying');
                exit;
            }
            $carryOverNewObj = CarryOverModel::create([
                    'date' => date('Y-m', $this->time),
                ]);
            DB::beginTransaction();
            try {
                $carryOverForms = $carryOver->forms;
                foreach($carryOverForms as $carryOverForm) {
                    $carryOverNewObj->forms()->create(['stock_id'=>$carryOverForm->stock_id, 
                                                    'begin_quantity' => $carryOverForm->over_quantity,
                                                    'begin_amount' => $carryOverForm->over_amount]);
                }
                $stockIns = InOutModel::where('outer_type', 'IN')->whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $this->time)])->get();
                $stockOuts = InOutModel::where('outer_type', 'OUT')->whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $this->time)])->get();
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
            $this->result['status'] = 'success';
            $this->result['remark'] = 'success.  Stock CarryOver';
            $this->log('StockCarrying');
        } else {
            var_dump('new');
            DB::beginTransaction();
            try {
                $carryOverNewObj = CarryOverModel::create([
                        'date' => date('Y-m', $this->time),
                    ]);
                $len = 1000;
                $start = 0;
                $stocks = StockModel::skip($start)->take($len)->get();
                while($stocks->count()) {
                    foreach($stocks as $stock)
                    {
                        $carryOverNewObj->forms()->create([
                                'stock_id' => $stock->id,
                                'over_quantity' => $stock->all_quantity,
                                'over_amount' => $stock->all_quantity * $stock->unit_cost,
                            ]);
                    }
                    $start += $len;
                    $stocks = StockModel::skip($start)->take($len)->get();
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
            $this->result['status'] = 'success';
            $this->result['remark'] = 'success.  Stock CarryOver';
            $this->log('StockCarrying');
        }
    }
}