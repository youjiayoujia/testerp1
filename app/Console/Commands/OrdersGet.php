<?php

namespace App\Console\Commands;

use App\Models\OrderModel;
use Channel;
use App\Models\CommandModel;
use App\Models\Channel\AccountModel;
use App\Jobs\DoPackage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

class OrdersGet extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:get {accountID}';

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
        $account = AccountModel::find($this->argument('accountID'));
        if ($account) {
            $begin = microtime(true);
            $startDate = date("Y-m-d H:i:s", strtotime('-1 day'));
            $endDate = date("Y-m-d H:i:s", time());
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $orderList = $channel->listOrders($startDate, $endDate, $account->api_status, 100);
            foreach ($orderList as $order) {
                $order['channel_id'] = $account->channel->id;
                $order['channel_account_id'] = $account->id;
                $order['status'] = 'PAID';
                $thisOrder = $orderModel
                    ->where('channel_ordernum', $order['channel_ordernum'])
                    ->first();
                //todo:是否要更新订单
                if (!$thisOrder) {
                    $thisOrder = $orderModel->createOrder($order);
                    $thisOrder->checkBlack();
                    $this->dispatch((new DoPackage($thisOrder))->onQueue('doPackages'));
                }
            }
            $end = microtime(true);
            $lasting = round($end - $begin, 3);
            $this->info($account->alias . ' 耗时' . $lasting . '秒');
            CommandModel::create([
                'account_id' => $account->id,
                'signature' => $this->signature,
                'description' => $this->description,
                'lasting' => $lasting,
                'result' => 'success.',
                'remark' => 'success.',
            ]);
        } else {
            $this->error('Account is not exist.');
        }
    }
}