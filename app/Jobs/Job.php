<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Log\QueueModel as QueueLog;

abstract class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "onQueue" and "delay" queue helper methods.
    |
    */

    use Queueable;

    protected $relation_id = 0;
    protected $description = 'init';
    protected $lasting = 0.00;
    protected $result = ['status' => 'init', 'remark' => 'init'];

    public function log()
    {
        QueueLog::create([
            'relation_id' => $this->relation_id,
            'queue' => __CLASS__,
            'description' => $this->description,
            'lasting' => $this->lasting,
            'result' => $this->result['status'],
            'remark' => $this->result['remark']
        ]);
    }
}
