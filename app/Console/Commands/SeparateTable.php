<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderModel;
use DB;

class SeparateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:separate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tmp = 90*24*3600;
        $date = date('Y-m-d 00:00:00' ,time()-$tmp);
        $orders = OrderModel::where('created_at','<',$date)->get();
        foreach($orders as $order){
            $season = ceil((date('n',strtotime($order->created_at)))/3);
            $year = ceil((date('Y',strtotime($order->created_at))));
            DB::select("CREATE TABLE if not exists `orders_".$year."_".$season."` SELECT * FROM orders WHERE 1=2");
            DB::select("INSERT INTO `orders_".$year."_".$season."` SELECT * FROM `orders` where `id` = ".$order->id." ");
            foreach ($order->items as $orderItem) {
                $season = ceil((date('n',strtotime($orderItem->created_at)))/3);
                $year = ceil((date('Y',strtotime($orderItem->created_at))));
                DB::select("CREATE TABLE if not exists `order_items_".$year."_".$season."` SELECT * FROM order_items WHERE 1=2");
                DB::select("INSERT INTO `order_items_".$year."_".$season."` SELECT * FROM `order_items` where `id` = ".$orderItem->id." ");
                $orderItem->forceDelete();
            }
            foreach ($order->packages as $package) {
                $season = ceil((date('n',strtotime($package->created_at)))/3);
                $year = ceil((date('Y',strtotime($package->created_at))));
            }
            $order->forceDelete();
        }
    }
}
