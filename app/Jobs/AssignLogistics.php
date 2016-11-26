<?php

namespace App\Jobs;

use Cache;
use Exception;
use App\Jobs\Job;
use App\Jobs\PlaceLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignLogistics extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'Package:' . $this->package->id . ' assign logistics.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        $order = $this->package->order;
        $this->package->assignLogistics();
        if ($order->status != 'REVIEW') {
            //验证黑名单
            if ($order->checkBlack()) {
                $order->update(['status' => 'REVIEW']);
                $order->remark('黑名单需审核.', 'BLACK');
            }
            //特殊需求
            if (!empty($order->customer_remark)) {
                $order->update(['status' => 'REVIEW']);
                $order->remark('特殊需求需审核.', 'REQUIRE');
            }
            //订单留言
            if ($order->messages->count() == 1 and $order->messages->first()->replies->count() == 0) {
                $order->update(['status' => 'REVIEW']);
                $order->remark('客户有订单留言.', 'MESSAGE');
            }
            //包裹重量大于2kg
            if ($this->package->weight >= 2) {
                $order->update(['status' => 'REVIEW']);
                $order->remark('包裹重量大于2kg.', 'WEIGHT');
            }
            //利润率判断
            $profitRate = $order->calculateProfitProcess();
            if ($profitRate <= 0 or $profitRate >= 0.4) {
                $order->update(['status' => 'REVIEW']);
                $order->remark('订单利润率小于0或者大于40%.', 'PROFIT');
            }
            if ($this->package->status == 'ASSIGNED') {
                if ($order->status != 'REVIEW') {
                    $job = new PlaceLogistics($this->package);
                    $job = $job->onQueue('placeLogistics');
                    $this->dispatch($job);
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success.';
                    $this->package->eventLog('队列', '已匹配物流，加入下单队列', json_encode($this->package));
                } else {
                    $this->result['status'] = 'success';
                    $this->result['remark'] = '订单需审核.';
                    $this->package->eventLog('队列', '订单需审核', json_encode($this->package));
                }
            } else {
                $this->result['status'] = 'success';
                $this->result['remark'] = '订单需审核.';
                $this->package->eventLog('队列', '订单需审核', json_encode($this->package));
            }
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail to assign logistics.';
            $this->package->eventLog('队列', 'Fail to assign logistics.', json_encode($this->package));
        }
        $this->lasting = round(microtime(true) - $start, 2);
        $this->log('AssignLogistics');
    }
}