<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderModel;
use App\Models\Log\QueueModel;
use App\Models\Log\CommandModel;
use DB;
use Tool;

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
        ini_set('memory_limit', '2048M');
        $tmp = 90*24*3600;
        //90天之前的订单删除主表数据，存入对应月份的分表
        $date = date('Y-m-d 00:00:00' ,time()-$tmp);
        $orders = OrderModel::where('created_at','<',$date)->get();
        
        foreach($orders as $order){
            //分表表名
            $table_name = Tool::getYearAndQuarter($order->created_at);
            //判断数据库是否存在对应年季度的表,没有就创建
            DB::select("CREATE TABLE if not exists `orders_".$table_name."` SELECT * FROM orders WHERE 1=2");
            //插入对应分表
            DB::select("INSERT INTO `orders_".$table_name."` SELECT * FROM `orders` where `id` = ".$order->id." ");
            foreach ($order->items as $orderItem) {
                DB::select("CREATE TABLE if not exists `order_items_".$table_name."` SELECT * FROM order_items WHERE 1=2");
                DB::select("INSERT INTO `order_items_".$table_name."` SELECT * FROM `order_items` where `id` = ".$orderItem->id." ");
                $orderItem->forceDelete();
            }
            foreach ($order->packages as $package) {
                DB::select("CREATE TABLE if not exists `packages_".$table_name."` SELECT * FROM packages WHERE 1=2");
                DB::select("INSERT INTO `packages_".$table_name."` SELECT * FROM `packages` where `id` = ".$package->id." ");
                foreach ($package->items as $packageItem) {
                    DB::select("CREATE TABLE if not exists `package_items_".$table_name."` SELECT * FROM package_items WHERE 1=2");
                    DB::select("INSERT INTO `package_items_".$table_name."` SELECT * FROM `package_items` where `id` = ".$packageItem->id." ");
                    $packageItem->forceDelete();
                }
                $package->forceDelete();
            }
            $order->forceDelete();
        }

        //队列日志表保留最近一个月
        $date30 = date('Y-m-d 00:00:00' ,time()-90*24*3600);
        $log_queues = QueueModel::where('created_at','<',$date30)->get();
        foreach($log_queues as $log_queue){
            $log_queue->forceDelete();
        }
        //任务日志保留最近一个月
        $log_commands = CommandModel::where('created_at','<',$date30)->get();
        foreach($log_commands as $log_command){
            $log_command->forceDelete();
        }

    }
}
