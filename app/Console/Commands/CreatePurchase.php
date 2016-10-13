<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;
use App\Models\Purchase\RequireModel;

class CreatePurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create PurchaseOrders';

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
        $begin = microtime(true);
        $itemModel = new ItemModel();
        if(date("H")!=12){
            $itemModel->createPurchaseNeedData();
            $end = microtime(true);
            echo '采购需求数据更新耗时' . round($end - $begin, 3) . '秒,正在自动创建采购单,请稍后......';
        }
   
        $requireModel = new RequireModel();
        $requireModel->createAllPurchaseOrder();
        $endcreate = microtime(true);
        echo '采购单创建完成,耗时'.round($endcreate - $end, 3).'秒';
    }
}
