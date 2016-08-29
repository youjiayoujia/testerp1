<?php

namespace App\Console\Commands;
use Channel;
use Illuminate\Console\Command;
use App\Models\Publish\Ebay\EbayFeedBackModel;
use App\Models\Channel\AccountModel;

class GetFeedBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getFeedBack:account{accountIDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get channel feedback information';

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
        $accountIds = explode(',', $this->argument('accountIDs'));
        foreach ($accountIds as $accountId) {
            $account = AccountModel::findOrFail($accountId);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->GetFeedback();
            foreach($result as $re){
                $re['channel_account_id'] = $accountId;
                $feedback = EbayFeedBackModel::where(['feedback_id'=>$re['feedback_id'],'channel_account_id'=>$accountId])->first();
                if(empty($feedback)){
                    EbayFeedBackModel::create($re);
                }
            }
        }
    }
}
