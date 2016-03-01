<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class dj extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels,Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fd = fopen('d:/dj.txt','a+');
        fwrite($fd, "hello ,dajie");
        fclose($fd);
    }
}
