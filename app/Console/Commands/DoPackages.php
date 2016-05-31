<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderModel;

class DoPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:do';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do Packages';

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
     * todo:订单优先级
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $len = 1000;
        $start = 0;
        $orders = OrderModel::where('active', 'NORMAL')
            ->whereIn('status', ['PREPARED', 'NEED'])
            ->orderBy('package_times', 'desc')->skip($start)->take($len)
            ->get();
        $begin = microtime(true);
        while(count($orders)) {
            foreach ($orders as $order) {
                echo $order->id . '<br>';
                $order->createPackage();
            }
            $start += $len;
            $orders = OrderModel::where('active', 'NORMAL')
            ->whereIn('status', ['PREPARED', 'NEED'])
            ->orderBy('package_times', 'desc')->skip($start)->take($len)
            ->get();
            break;
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
}
