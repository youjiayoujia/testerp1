<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Message\MessageModel;
use App\Models\Message\MessageAttachment;
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
                $messageNew = MessageModel::firstOrNew(['message_id' => $message['message_id']]);
                if($messageNew->id == null){
                    $messageNew->account_id = $account->id;
                    $messageNew->message_id = $message['message_id'];
                    $messageNew->from_name = $message['from_name'];
                    $messageNew->labels = $message['labels'];
                    $messageNew->label = $message['label'];
                    $messageNew->from = $message['from'];
                    $messageNew->to = $message['to'];
                    $messageNew->date = $message['date'];
                    $messageNew->subject = $message['subject'];
                    $messageNew->content = $message['content'];

                    $messageNew->status  = 'UNREAD';
                    $messageNew->related  = 0;
                    $messageNew->required  = 0;
                    $messageNew->read  = 0;

                    $messageNew->save();
                    $this->info('Message #' . $messageNew->message_id . ' Received.');

                    //附件写入
                    $messageInsert = MessageModel::firstOrNew(['message_id' => $message['message_id']]);
                    if($messageInsert){
                        if($message['attachment'] !=''){
                            foreach ($message['attachment'] as $value){
                                if($value){
                                    $attachment = MessageAttachment::firstOrNew(['message_id' => $messageInsert->message_id]);
                                    $attachment->message_id =$messageInsert->id;
                                    $attachment->gmail_message_id =$messageInsert->message_id;
                                    $attachment->filename = $value['file_name'];
                                    $attachment->filepath = $value['file_path'];
                                    $attachment->save();
                                }
                            }
                        }
                    }
                }else{
                    $this->comment('Message #' . $messageNew->message_id . ' alerady exist.');

                }
                

            }
        }
    }
}
