<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotWarehouseInSendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

    }
    /**
     * 下单7天未到货
     *
     * @param none
     * @return obj
     *
     */
    public function sevenPurchaseSku()
    {
        $time = date('Y-m-d H:i:s',time()-60*60*24*7);
        $purchaseOrder = $this->model->where('created_at','<',$time)->whereIn('status',['1','2','3'])->get();

        //邮件模板数据
        $data = ['email'=>'549991570@qq.com', 'name'=>'youjiatest@163.com','purchaseOrder'=>$purchaseOrder];
        //发送邮件
        Mail::send('purchase.purchaseOrder.mailSevenPurchase', $data, function($message) use($data){
            $message->to($data['email'], $data['name'])->subject('采购单7天未到货');
        });
    }
}
