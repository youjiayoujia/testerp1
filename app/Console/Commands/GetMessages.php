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
        /**
         * 获取Amazon 平台邮件
         */
        $channel = ChannelsModel::where('name','Amazon')->first();
        $account = AccountModel::find($channel->id);
        $platform = Channel::driver($account->channel->driver, $account->api_config);
        $platform->getMessages();

    }
}
