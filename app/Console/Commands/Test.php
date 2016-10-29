<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;
use App\Models\Purchase\RequireModel;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test';

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
        $itemModel->updateWarehouse();
        $itemModel->updateUser();
        $itemModel->updateOldData();
        $itemModel->updateBasicData();
        $end = microtime(true);
    }
}
