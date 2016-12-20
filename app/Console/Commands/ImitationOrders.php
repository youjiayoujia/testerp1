<?php

namespace App\Console\Commands;

use factory;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ImitationOrders extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imitate:orders {quantity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'imitate orders';

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
        $quantity = $this->argument('quantity');
        $orders = factory(\App\Models\OrderModel::class,(int)$quantity)->create(['status' => 'PREPARED','customer_service' => '63', 'operator' => '195', 'payment' => 'MIXEDCARD', 'currency' => 'USD', 'rate' => '1'])
        ->each(function($single){
            $i = 0;
            $range = mt_rand(1,3);
            while($i<$range) {
                $single->items()->save(factory(\App\Models\Order\ItemModel::class)->make(['currency' => 'USD', 'is_active' => '1', 'status' => 'NEW', 'item_status' => 'selling']));
                $i++;
            }
        });
        $this->info('imitation orders data is success, quantity:'.$quantity);
    }
}