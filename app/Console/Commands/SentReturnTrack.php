<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\PackageModel;
use App\Jobs\ReturnTrack;
use Illuminate\Foundation\Bus\DispatchesJobs;



class SentReturnTrack extends Command
{
    use  DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentReturnTrack:get {accountID}';

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
        $account_id =  $this->argument('accountID');
        $driver = AccountModel::find($this->argument('accountID'))->channel->driver;

        if($driver=='amazon'){

        }elseif($driver=='aliexpress'){

            $packages = PackageModel::where(['channel_account_id'=>$account_id,'is_mark'=>'0','order_id'=>7222])->where('tracking_no','!=','' )->whereHas('order', function ($query)  {
                $query = $query->where('orders.created_at','>=', '2016-07-03' );
            })->get();

        }elseif($driver=='wish'){


            $packages = PackageModel::where(['channel_account_id'=>$account_id,'is_mark'=>'0','order_id'=>5474])->where('tracking_no','!=','' )->whereHas('order', function ($query)  {
                $query = $query->where('orders.created_at','>=', '2016-07-03' );
            })->get();

        }elseif($driver=='ebay'){
            $packages = PackageModel::where(['channel_account_id'=>$account_id,'is_mark'=>'0'])->where('tracking_no','!=','' )->whereHas('order', function ($query)  {
                $query = $query->where('orders.created_at','>=', '2016-07-03' );
            })->get();
        }elseif($driver=='lazada'){

        }elseif($driver=='cdiscount'){

        }else{
            echo 'error!';
        }


        foreach($packages as $package){



            $job = new ReturnTrack($package);
            $job = $job->onQueue('returnTrack');
            $this->dispatch($job);

        }





























    }
}
