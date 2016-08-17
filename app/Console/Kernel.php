<?php
namespace App\Console;

use App\Models\ChannelModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\DoPackages::class,
        \App\Console\Commands\GetOrders::class,
        \App\Console\Commands\CreatePurchase::class,
        \App\Console\Commands\TransferProduct::class,
        \App\Console\Commands\TransferChannelAccount::class,
        \App\Console\Commands\TransferSupplier::class,
        \App\Console\Commands\TransferStock::class,
        \App\Console\Commands\TransferLogistics::class,
        \App\Console\Commands\ChannelLogistics::class,
        \App\Console\Commands\TransferUser::class,
        \App\Console\Commands\GetWishProduct::class,
        \App\Console\Commands\GetAliexpressProduct::class,
        //邮件
        \App\Console\Commands\GetMessages::class,
        \App\Console\Commands\SendMessages::class,
        \App\Console\Commands\SetMessageRead::class,
        \App\Console\Commands\GetGmailCredentials::class,
        \App\Console\Commands\SentReturnTrack::class,
        \App\Console\Commands\MatchPaypal::class,
        \App\Console\Commands\GetLazadaPackageId::class,
        \App\Console\Commands\GetFeedBack::class,
        \App\Console\Commands\SentFeedBack::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        //抓单定时任务规则
        foreach (ChannelModel::all() as $channel) {
            switch ($channel->driver) {
                case 'amazon':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
//                case 'aliexpress':
//                    foreach ($channel->accounts as $account) {
//                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
//                    }
//                    break;
                case 'wish':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'ebay':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'lazada':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'cdiscount':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
            }
        }
    }
}