<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductModel;
use App\Jobs\ImportImages;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ProductImage extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:create';

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
    public function handle(ProductModel $product)
    {
        ini_set('memory_limit', '2048M');
        foreach ($product->all() as $model) {
            $job = new ImportImages($model);
            $job = $job->onQueue('importImages');
            $this->dispatch($job);
        }
    }
}
