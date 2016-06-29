<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;

class CreatePurchaseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchaseData:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create PurchaseData';

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
        $itemModel->createPurchaseNeedData();
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
}
