<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Alibaba\Alibaba;
use App\Models\Purchase\PurchasePostageModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\AlibabaSupliersAccountModel;


class GetAliShipmentNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliShipmentName:get';

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
        $ali =  new Alibaba(); //初始化阿里账号

        //AlibabaSupliersAccountModel::

        $orderId = '2536526862777938';


        $date = date('Y-m-d H:i:s', strtotime("-6 hours"));
        $param = '';




        $ppp = AlibabaSupliersAccountModel::where('resource_owner','sala099')->first();



        $curl_params['access_token'] =$ppp->access_token;
        $curl_params['buyerMemberId'] =$ppp->memberId;
        $curl_params['orderIdSet'] = '[2536526862777938]';

        //$curl_params['_aop_appKey'] =$ali->app_key;


        $param['buyerMemberId'] = $curl_params['buyerMemberId'];
        $param['access_token'] = $curl_params['access_token'];
        $param['orderIdSet'] = $curl_params['orderIdSet'];

        $uri_path = 'param2/2/cn.alibaba.open/trade.order.list.get/1023183';


        $curl_params['_aop_signature'] = $ali->getSignature($param, $ali->order_list_api_url.'/'.$ali->app_key);



        $crul_url = $ali->ali_url .'/openapi/'.$ali->order_list_api_url.'/'.$ali->app_key;
        $orderList = json_decode($ali->get($crul_url,$curl_params),true);

        foreach($orderList['orderListResult']['modelList'] as $model){

            
            dd($model['logisticsStatus']['logisticsOrderList']);
        }










        $postages = PurchasePostageModel::where('purchase_order_id','<>','')->where('post_coding','=',Null)->get();
        foreach ($postages as $postage){
            if(!empty($postage->purchaseOrder->post_coding)){ //外部单号


                dd($postage->purchaseOrder->post_coding);
            }
        }

    }





}
