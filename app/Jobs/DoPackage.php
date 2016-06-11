<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\AssignLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DoPackage extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->order->createPackage();
        if ($this->order->status == 'PACKED') {
            foreach ($this->order->packages as $package) {
                $this->dispatch((new AssignLogistics($package))->onQueue('assignLogistics'));
            }
        }
        if ($this->order->status == 'NEED') {
            $this->release(60);
//            $this->dispatch((new DoPackage($this->order))->onQueue('redoPackages'));
        }
    }
}
