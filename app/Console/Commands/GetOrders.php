<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Jobs\InOrders;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\Channel\AccountModel;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetOrders extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:orders {accountIDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Orders From Channels.';

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
    public function handle()
    {
        $start = microtime(true);
        $accountIds = explode(',', $this->argument('accountIDs'));
        foreach ($accountIds as $accountId) {
            $account = AccountModel::find($accountId);
            $commandLog = CommandLog::create([
                'relation_id' => $account->id,
                'signature' => __CLASS__,
                'description' => 'get orders form ' . $account->channel->name . ':' . $account->alias . '[' . $account->id . '].',
                'lasting' => 0,
                'total' => 0,
                'result' => 'init',
                'remark' => 'init',
            ]);
            $total = 0;
            $response = '';
            if ($account) {
                $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
                $endDate = date("Y-m-d H:i:s", time() - 300);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $orderList = $channel->listOrders($startDate, $endDate, $account->api_status, $account->sync_pages);
                foreach ($orderList as $order) {
                    $order['channel_id'] = $account->channel->id;
                    $order['channel_account_id'] = $account->id;
                    $order['customer_service'] = $account->customer_service ? $account->customer_service->id : 0;
                    $order['operator'] = $account->operator ? $account->operator->id : 0;
                    $job = new InOrders($order);
                    $job = $job->onQueue('inOrders');
                    $this->dispatch($job);
                    $total++;
                }
                $response = json_encode($orderList);
                //todo::Adapter->error()
                $result['status'] = 'success';
                $result['remark'] = 'Success.';
            } else {
                $result['status'] = 'fail';
                $result['remark'] = 'Account is not exist.';
            }
            $end = microtime(true);
            $lasting = round($end - $start, 3);
            $commandLog->data = $response;
            $commandLog->lasting = $lasting;
            $commandLog->total = $total;
            $commandLog->result = $result['status'];
            $commandLog->remark = $result['remark'];
            $commandLog->save();
            $this->info($account->alias . ':' . $account->id . ' 耗时' . $lasting . '秒');
        }
    }
}