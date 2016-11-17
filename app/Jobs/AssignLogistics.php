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
        $start = microtime(true);
        $this->package->assignLogistics();
        if ($this->package->status == 'ASSIGNED') {
            //计算订单利润率
            $orderRate = $this->package->order->calculateProfitProcess();
            if ($orderRate > 0 && $orderRate < 0.4) {
                $job = new PlaceLogistics($this->package);
                $job = $job->onQueue('placeLogistics');
                $this->dispatch($job);
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success.';
                $this->package->eventLog('队列', '已匹配物流，加入下单队列',json_encode($this->package));
            } else {
                $order = $this->package->order;
                $order->OrderCancle();
                $this->result['status'] = 'fail';
                $this->result['remark'] = "Order's profit isn't more than 0.";
            }
            $this->package->order->update(['profit_rate' => $orderRate]);
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to assign logistics.';
            $this->package->eventLog('队列', 'Fail to assign logistics.',json_encode($this->package));
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('AssignLogistics');
    }
}