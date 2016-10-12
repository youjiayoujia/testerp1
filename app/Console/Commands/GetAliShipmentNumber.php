<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Alibaba\Alibaba;
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $ali =  new Alibaba();
        $postages = PurchasePostageModel::where('purchase_order_id','<>','')->where('post_coding','=',Null)->get();
        foreach ($postages as $postage){
            if(!empty($postage->purchaseOrder->post_coding)){ //外部单号


                dd($postage->purchaseOrder->post_coding);
            }
        }

    }
}
