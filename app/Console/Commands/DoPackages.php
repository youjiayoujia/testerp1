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
     * @return mixed
     */
    public function handle()
    {
        $t1 = microtime(true);
//        $orders = OrderModel::limit(100)->get();
        $orders = OrderModel::all();
        foreach ($orders as $order) {
            $order->createPackage();
        }
        $t2 = microtime(true);
        $this->info('耗时' . round($t2 - $t1, 3) . '秒');
    }
}
