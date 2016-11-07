<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\AssignStocks;

class PlaceLogistics extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package, $type = null)
    {
        $this->package = $package;
        $this->type = $type;
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
        $result = $this->package->placeLogistics($this->type);
        var_dump($result['status']);
        if ($result['status'] == 'success') {
            var_dump('123');
            $this->result['status'] = 'success';
            $this->result['remark'] = 'packages tracking_no:' . $result['tracking_no'];
            $item = $this->package->items->first();
            var_dump(empty($item->warehouse_position_id));
            if(empty($item->warehouse_position_id)) {
                $job = new AssignStocks($this->package);
                $job = $job->onQueue('assignStocks');
                $this->dispatch($job);
                $this->result['status'] = 'success';
                $this->result['remark'] = 'packages tracking_no:' . $result['tracking_no'] . 'Need AssignStocks';
            }
        } elseif ($result['status'] == 'again') {
            $this->result['status'] = 'success';
            $this->result['remark'] = 'packages logistics_order_number:' . $result['logistics_order_number'] . ' need  get tracking_no ';
            $job = new PlaceLogistics($this->package, $this->type);
            $job = $job->onQueue('placeLogistics')->delay(600); //暂设10分钟
            $this->dispatch($job);
        } else {
            $this->release();
            $this->result['status'] = 'fail';
            $this->result['remark'] = $result['tracking_no'];
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('PlaceLogistics');
    }
}