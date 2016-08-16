<?php

namespace App\Jobs;

use App\Jobs\Job;
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
        if ($this->package->createPackageItems()) {
            if ($this->package->status == 'WAITASSIGN') {
                $job = new AssignLogistics($this->package);
                $job = $job->onQueue('assignLogistics');
                $this->dispatch($job);
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success to assign stock.';
            } elseif ($this->package->status == 'NEED') {
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Out of stock.';
            }
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to assign stock.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('assignStocks');
    }
}