<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TestController as mtest;

class test1 extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $test;
    public function __construct(mtest $test)
    {
        $this->test = $test;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        if($this->attempts() > 2)
            $this->delete();
        $this->test->wri();
        $this->delete();
    }
}
