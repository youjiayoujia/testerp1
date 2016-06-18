<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        //获得require表需要采购的item_id
        $require = new RequireModel();
        $items = $require->where('is_require',1)->get()->toArray();
        $require->getNeedPurchaseNum($items);
        
    }
}
