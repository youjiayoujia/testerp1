<?php

namespace App\Console;

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
        \App\Console\Commands\OrdersGet::class,
        \App\Console\Commands\CreatePurchase::class,
        \App\Console\Commands\CreatePurchaseData::class,
        \App\Console\Commands\TransferProduct::class,
        \App\Console\Commands\TransferChannelAccount::class,
        \App\Console\Commands\TransferSupplier::class,
        \App\Console\Commands\TransferStock::class,
        \App\Console\Commands\TransferLogistics::class,
        \App\Console\Commands\ChannelLogistics::class,
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
        $schedule->command('orders:get')->everyFiveMinutes();
    }
}
