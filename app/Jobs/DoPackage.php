<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\AssignLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoPackage extends Job implements SelfHandling, ShouldQueue
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
        $this->relation_id = $this->order->id;
        $this->description = 'Order:' . $this->order->id . ' do package.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->order->createPackage()) {
            if ($this->order->status == 'PACKED') {
                foreach ($this->order->packages as $package) {
                    $job = new AssignLogistics($package);
                    $job = $job->onQueue('assignLogistics');
                    $this->dispatch($job);
                }
            }
            if ($this->order->status == 'NEED') {
                $this->release(3600);
            }
            $this->result['status'] = 'success';
            $this->result['remark'] = 'Success.';
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to do package.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log();
    }
}