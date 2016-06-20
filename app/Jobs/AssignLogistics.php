<?php

namespace App\Jobs;

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
        if ($this->package->assignLogistics()) {
            //计算订单利润率
            $this->package->calculateProfitProcess();
            $job = new PlaceLogistics($this->package);
            $job = $job->onQueue('orderLogistics');
            $this->dispatch($job);
            $this->result['status'] = 'success';
            $this->result['remark'] = 'Success.';
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to assing logistics.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log();
    }
}