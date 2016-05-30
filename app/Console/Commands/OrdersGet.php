<?php

namespace App\Console\Commands;

use Channel;
use App\Models\OrderModel;
use App\Models\CommandModel;
use App\Models\Channel\AccountModel;
use Illuminate\Console\Command;

class OrdersGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Orders.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(OrderModel $orderModel)
    {
        foreach (AccountModel::all() as $account) {
            $begin = microtime(true);
            $startDate = date("Y-m-d H:i:s", strtotime('-30 day'));
            $endDate = date("Y-m-d H:i:s", strtotime('-12 hours'));
            $status = $account->api_status;
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $orderList = $channel->listOrders($startDate, $endDate, $status, 100);
            foreach ($orderList as $order) {
                $thisOrder = $orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
                if ($thisOrder) {
                    $thisOrder->updateOrder($order);
                } else {
                    $order['channel_id'] = $account->channel->id;
                    $order['channel_account_id'] = $account->id;
                    $orderModel->createOrder($order);
                }
            }
            $end = microtime(true);
            $lasting = round($end - $begin, 3);
            $this->info($account->alias . ' 耗时' . $lasting . '秒');
        }
        CommandModel::create([
            'signature' => $this->signature,
            'description' => $this->description,
            'lasting' => $lasting,
            'result' => 'success.',
            'remark' => 'success.',
        ]);
    }
}