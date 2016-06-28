<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Channel\ChannelsModel;

use Tool;


class GetMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Messages';

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
        //遍历账号
        foreach (AccountModel::all() as $account) {
            //实例化渠道驱动
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            //获取Message列表
            $messageList = $channel->getMessages();
            foreach ($messageList as $message) {
                $message['channel_account_id'] = $account->id;
                $message['assign_id'] = 0;
                $message['status'] = 'UNREAD';
                $message['related'] = 0;
                $message['required'] = 0;
                $message['read'] = 0;
                //returned
                $message['title'] = '';
                $message['from_name'] = '';
                $message['from_email'] = '';
                $message['to_name'] = '';
                $message['to_email'] = '';
                $message['date'] = '';
                $message['content'] = '';
                $message['attechment'] = '';
                //todo:Insert Message
            }
        }
        /**
         * 获取Amazon 平台邮件
         */
//        $channel = ChannelsModel::where('name','Amazon')->first();
//        $account = AccountModel::find($channel->id);
//        $platform = Channel::driver($account->channel->driver, $account->api_config);
//        $platform->getMessages();

    }
}
