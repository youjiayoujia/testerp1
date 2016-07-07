<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Channel\AccountModel;
use Channel;


class SendMessages extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        //
        $this->reply = $reply;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        //遍历账号
        foreach (AccountModel::all() as $account) {

            if($account->channel->driver == 'amazon' && $account->message_secret !=''){ //亚马逊渠道邮件
                $channel = Channel::driver($account->channel->driver, $account->api_config);

                $channel->sendMessages($account);//发送渠道message

            }

        }

    }
}
