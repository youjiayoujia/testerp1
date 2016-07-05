<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class SendMessage extends Job implements SelfHandling
{
    protected $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->reply->status = 'SENT';
        $this->reply->save();
    }
}
