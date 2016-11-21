<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use App\Jobs\AssignLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignStocks extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'Package:' . $this->package->id . ' do package.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if (in_array($this->package->status, ['NEW', 'NEED']) && $this->package->order->status != 'REVIEW' && $this->package->createPackageItems()) {
            if ($this->package->status == 'WAITASSIGN') {
                $job = new AssignLogistics($this->package);
                $job = $job->onQueue('assignLogistics');
                $this->dispatch($job);
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success to assign stock.';
            } elseif ($this->package->status == 'PROCESSING') {
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success to assign stock.';
                $this->package->eventLog('队列', '已匹配到库存,待拣货', json_encode($this->package));
            }
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'status error or cannt assign stocks';
            $this->package->eventLog('队列', 'status error or order status review or cannt assign stocks',json_encode($this->package));
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('assignStocks');
    }
}