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
        $accountIds = explode(',', $this->argument('accountIDs'));
        foreach ($accountIds as $accountId) {
            $account = AccountModel::find($accountId);
            if ($account) {
                //初始化
                $i = 1;
                $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
                $endDate = date("Y-m-d H:i:s", time() - 300);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $nextToken = '';
                do {
                    $start = microtime(true);
                    $total = 0;
                    $commandLog = CommandLog::create([
                        'relation_id' => $account->id,
                        'signature' => __CLASS__,
                        'description' => 'get orders form ' . $account->channel->name . ':' . $account->alias . '[' . $account->id . '] - ' . $i . '.',
                        'lasting' => 0,
                        'total' => 0,
                        'result' => 'init',
                        'remark' => 'init',
                    ]);
                    $orderList = $channel->listOrders(
                        $startDate, //开始日期
                        $endDate, //截止日期
                        $account->api_status, //订单状态
                        $account->sync_pages, //每页数量
                        $nextToken //下一页TOKEN
                    );
                    foreach ($orderList['orders'] as $order) {
                        $order['channel_id'] = $account->channel->id;
                        $order['channel_account_id'] = $account->id;
                        $order['customer_service'] = $account->customer_service ? $account->customer_service->id : 0;
                        $order['operator'] = $account->operator ? $account->operator->id : 0;
                        $job = new InOrders($order);
                        $job = $job->onQueue('inOrders');
                        $this->dispatch($job);
                        $total++;
                    }
                    $nextToken = $orderList['nextToken'];
                    //todo::Adapter->error()
                    $result['status'] = 'success';
                    $result['remark'] = 'Success.';
                    $end = microtime(true);
                    $lasting = round($end - $start, 3);
                    $commandLog->update([
                        'data' => serialize($orderList['orders']),
                        'lasting' => $lasting,
                        'total' => $total,
                        'result' => $result['status'],
                        'remark' => $result['remark'],
                    ]);
                    $this->info($account->alias . ':' . $account->id . ' 抓取取第 ' . $i . ' 页, 耗时 ' . $lasting . ' 秒');
                    $i++;
                } while ($nextToken);
            } else {
                $this->error('Account is not exist.');
            }
        }
    }
}