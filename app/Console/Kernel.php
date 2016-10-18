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
        \App\Console\Commands\PurchaseStaticstics::class,
        \App\Console\Commands\TransferProduct::class,
        \App\Console\Commands\TransferChannelAccount::class,
        \App\Console\Commands\TransferSupplier::class,
        \App\Console\Commands\TransferStock::class,
        \App\Console\Commands\ImportPosition::class,
        \App\Console\Commands\ImportStock::class,
        \App\Console\Commands\TransferLogistics::class,
        \App\Console\Commands\ChannelLogistics::class,
        \App\Console\Commands\TransferUser::class,
        \App\Console\Commands\GetWishProduct::class,
        \App\Console\Commands\GetEbayProduct::class,
        \App\Console\Commands\GetAliexpressProduct::class,
        \App\Console\Commands\GetJoomProduct::class,
        \App\Console\Commands\ProductImage::class,
        \App\Console\Commands\PickReport::class,
        \App\Console\Commands\PackReport::class,
        \App\Console\Commands\AllReport::class,
        //邮件
        \App\Console\Commands\GetMessages::class,
        \App\Console\Commands\SendMessages::class,
        \App\Console\Commands\SetMessageRead::class,
        \App\Console\Commands\GetGmailCredentials::class,
        \App\Console\Commands\SentReturnTrack::class,
        \App\Console\Commands\MatchPaypal::class,
        \App\Console\Commands\GetLazadaPackageId::class,
        \App\Console\Commands\GetLazadaProducts::class,
        \App\Console\Commands\GetFeedBack::class,
        \App\Console\Commands\SentFeedBack::class,
        \App\Console\Commands\GetEbayCases::class,
        \App\Console\Commands\GetAliexpressIssues::class,
        \App\Console\Commands\getSellmoreSuppliers::class,
        \App\Console\Commands\SetSkuStockZero::class,
        \App\Console\Commands\SetSkuStockZeroBak::class,
        \App\Console\Commands\uploadSmtOrderOnline::class,
        \App\Console\Commands\getSmtTrackNoOnline::class,
        \App\Console\Commands\autoAddMessageForSmtOrders::class,
        \App\Console\Commands\GetAliShipmentNumber::class,
        \App\Console\Commands\AutoGetMessageAliexpress::class,
        \App\Console\Commands\AutoGetWishMessage::class,

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
        $schedule->command('purchase:create')->cron('20 0,12 * * *');
        //抓单定时任务规则
        foreach (ChannelModel::all() as $channel) {
            switch ($channel->driver) {
                case 'amazon':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'aliexpress':
                    foreach ($channel->accounts as $account) {
                        $schedule->command('get:orders ' . $account->id)->cron('2 6,18,22 * * *');
                    }
                    break;
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
        $schedule->command('pick:report')->hourly();
        $schedule->command('pick:report')->dailyAt('1:5');
        $schedule->command('all:report')->daily();
        //CRM
        $schedule->command('AutoMessageAliexpress:get')->cron('8,40 15 * * *');
        $schedule->command('AutoEbayMessage:get')->everyFiveMinutes();
        $schedule->command('AutoWishMessage:get')->cron('8,12,13,14,16,30 17 * * *');
        $schedule->command('getEbayCases')->cron('8,12,13,14,16,30 17 * * *');
        $schedule->command('getFeedBack:account')->everyTenMinutes();

        //采购
        $schedule->command('aliShipmentName:get')->hourly();




    }
}