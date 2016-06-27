<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlaceLogistics extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'Package:' . $this->package->id . ' place logistics order.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->package->placeLogistics()) {
            $this->result['status'] = 'success';
            $this->result['remark'] = 'Success.';
        } else {
            $this->release();
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to place logistics order.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log();
    }
}