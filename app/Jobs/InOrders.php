<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\DoPackage;
use App\Models\OrderModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class InOrders extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'Put Order:[' . $this->order['channel_account_id'] . ':' . $this->order['channel_ordernum'] . '] in SYS.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderModel $orderModel)
    {
        $start = microtime(true);
        $oldOrder = $orderModel->where('channel_ordernum', $this->order['channel_ordernum'])->first();
        if (!$oldOrder) {
            $order = $orderModel->createOrder($this->order);
            if ($order->status == 'PREPARED') {
                $job = new DoPackage($order);
                $job->onQueue('doPackages');
                $this->dispatch($job);
                $this->relation_id = $order->id;
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success.';
            } else {
                $this->relation_id = 0;
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'Fail to put order in.';
            }
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log(__CLASS__);
    }
}