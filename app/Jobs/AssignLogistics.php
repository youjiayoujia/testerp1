<?php

namespace App\Jobs;

use Cache;
use Exception;
use App\Jobs\Job;
use App\Jobs\PlaceLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignLogistics extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package)
    {
        $this->package = $package;
        $this->relation_id = $this->package->id;
        $this->description = 'Package:' . $this->package->id . ' assign logistics.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!Cache::store('file')->get('stockIOStatus')) {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'stockTaking , stock is locked.';
            $this->lasting = 0;
            $this->log('AssignLogistics');
            throw new Exception('in stock taking');
        } else {
            $start = microtime(true);
            $this->package->assignLogistics();
            if ($this->package->status == 'ASSIGNED') {
                //计算订单利润率
                $orderRate = $this->package->order->calculateProfitProcess();
                if ($orderRate > 0) {
                    $job = new PlaceLogistics($this->package);
                    $job = $job->onQueue('placeLogistics');
                    $this->dispatch($job);
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success.';
                } else {
                    $this->result['status'] = 'fail';
                    $this->result['remark'] = "Order rate isn't more than 0.";
                }
            } else {
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'Fail to assign logistics.';
            }
            $this->lasting = round(microtime(true) - $start, 3);
            $this->log('AssignLogistics');
        }
    }
}