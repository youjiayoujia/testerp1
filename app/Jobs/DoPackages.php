<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use App\Jobs\AssignStocks;
use App\Models\OrderModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoPackages extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->description = 'DoPackages';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->order) {
            if ($this->order->status == 'PREPARED') {
                $oldPackages = $this->order->packages;
                foreach($oldPackages as $oldPackage) {
                    foreach($oldPackage->items as $packageItem) {
                        $packageItem->delete();
                    }
                    $oldPackage->delete();
                }
                $package = $this->order->createPackage();
                if ($package) {
                    $job = new AssignStocks($package);
                    $job->onQueue('assignStocks');
                    $this->dispatch($job);
                    $this->relation_id = $this->order->id;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success.';
                    $package->eventLog('队列', '已生成空包裹，加入匹配库存队列',json_encode($package));
                } else {
                    $this->relation_id = 0;
                    $this->result['status'] = 'fail';
                    $this->result['remark'] = 'Fail to create virtual package.';
                }
            } else {
                $this->relation_id = 0;
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Order status is not PREPARED. Can not create package';
                $this->order->eventLog('队列', 'Order status is not PREPARED. Can not create package',json_encode($package));
            }
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('DoPackages', json_encode($this->order));
    }
}