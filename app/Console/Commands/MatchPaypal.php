<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderModel;
use App\Jobs\MatchPaypal as MatchPaypalJob ;
use Illuminate\Foundation\Bus\DispatchesJobs;


class MatchPaypal extends Command
{
    use  DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match:account{accountIDs=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ebay order match Paypal';

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

        $accounts = $this->argument('accountIDs');
        $list = OrderModel::where([
            'order_is_alert'=>0,
            'channel_id'=>4,
        ])->where('status','!=','UNPAID')->where('status','!=','REVIEW')->where('created_at','>=',date('Y-m-d', strtotime('-4 day')));
        if($accounts !='all'){
            $accountsArr = explode(',',$accounts);
            $list->whereIn('channel_account_id',$accountsArr);
        }
        $result =  $list->get();
        foreach($result as $order){
            $job = new MatchPaypalJob($order);
            $job = $job->onQueue('MatchPaypal');
            $this->dispatch($job);
        }


        //
    }
}
