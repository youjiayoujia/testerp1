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
        $begin = microtime(true);
        $orders = OrderModel::where('active', 'NORMAL')
            ->whereIn('status', ['PREPARED', 'NEED'])
//            ->orderBy('package_times','desc')
            ->get();
        $orders = $orders->sortByDesc('package_times');
//        $orders = OrderModel::all();
        foreach ($orders as $order) {
            $this->info($order->id);
//            $order->createPackage();
        }
        $end = microtime(true);
        $this->info('耗时' . round($end - $begin, 3) . '秒');
    }
}
