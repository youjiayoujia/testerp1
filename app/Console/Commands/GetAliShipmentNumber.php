<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Paypal\Alibaba;
use App\Models\Purchase\PurchasePostageModel;
use App\Models\Purchase\PurchaseOrderModel;

class GetAliShipmentNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliShipmentName:get';

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

        
        echo 123;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
