<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\DoPackages;
use App\Jobs\AutoAddProduct;
use App\Models\OrderModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ChannelModel;

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
        $channel = ChannelModel::where('name', 'Ebay')->first();
        $start = microtime(true);
        $oldOrder = $orderModel->where('channel_ordernum', $this->order['channel_ordernum'])->first();
        if (!$oldOrder) {
            $order = $orderModel->createOrder($this->order);
            if ($order) {
                if ($order->status == 'PREPARED') {
                    $job = new DoPackages($order);
                    $job->onQueue('doPackages');
                    $this->dispatch($job);
                    $this->relation_id = $order->id;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success.';
                } else {
                    $this->relation_id = 0;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Order status is not PREPARED. Can not create package';
                }
            } else {
                $this->relation_id = 0;
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'Fail to put order in.';
            }
        } else {
            $channel = ChannelModel::where('name', 'Ebay')->first();
            if ($oldOrder->channel_id == $channel->id && $oldOrder->status == 'UNPAID' && $this->order['status'] == 'PAID') {//ebay  以前是UNPAID  现在是PAID 需要更新
                $this->order['id'] = $oldOrder->id;
                $order = $orderModel->updateOrder($this->order, $oldOrder);
                if ($order) {
                    if ($order->status == 'PAID') {
                        $order->update(['status' => 'PREPARED']);
                    }
                    $job = new DoPackages($order);
                    $job->onQueue('doPackages');
                    $this->dispatch($job);
                    $this->relation_id = $oldOrder->id;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'UNPAID to PAID. to PREPARED';
                } else {
                    $this->relation_id = 0;
                    $this->result['status'] = 'fail';
                    $this->result['remark'] = 'Fail to update to PAID.';
                }
            } else {
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Order has been exist.';
            }
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('InOrders', base64_encode(serialize($this->order)));
    }
}