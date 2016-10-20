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
        $ali = new Alibaba(); //初始化阿里账号
        $purchaseOrders =  PurchaseOrderModel::all();
        $orderids = '';
        $orderids_ary = [];
        $count = 0;

        foreach ($purchaseOrders as $key => $order){
            $count += 1;
            if(!empty($order->post_coding) && !empty($order->user_id)){
                if(($count) % 20 == 0 || $count == count($purchaseOrders)){
                    if(!empty($orderids)){
                        $orderids_ary[$order->user_id][] = $orderids;
                        $orderids =  '';
                    }
                }
                $orderids = empty($orderids) ? $order->post_coding : $orderids . ',' . $order->post_coding ;
            }
        }
        if(!empty($orderids_ary)){
            foreach ($orderids_ary as $user_id => $orderids){
                $account = AlibabaSupliersAccountModel::where('purchase_user_id',$user_id)->first();
                if(!empty($account)){
                    foreach ($orderids as $item){
                        //根据采购人 获取对应的阿里账号
                        $curl_params['access_token']  =$account->access_token;
                        $curl_params['buyerMemberId'] =$account->memberId;
                        $curl_params['orderIdSet']    = '['.$item.']';

                        $param['buyerMemberId'] = $curl_params['buyerMemberId'];
                        $param['access_token']  = $curl_params['access_token'];
                        $param['orderIdSet']    = $curl_params['orderIdSet'];
                        $curl_params['_aop_signature'] = $ali->getSignature($param, $ali->order_list_api_url.'/'.$ali->app_key);
                        $crul_url = $ali->ali_url .'/openapi/'.$ali->order_list_api_url.'/'.$ali->app_key;

                        $orderList = json_decode($ali->get($crul_url,$curl_params),true);

                        if(is_array($orderList['orderListResult']['modelList'])){
                            foreach ($orderList['orderListResult']['modelList'] as $modellist){
                                if(!empty($modellist['logisticsOrderList']) && is_array($modellist['logisticsOrderList'])){ //如果存在物流列表
                                    foreach ($modellist['logisticsOrderList'] as $logistic){
                                        if(!empty($logistic['logisticsOrderNo'])){
                                            $postage = PurchasePostageModel::where('post_coding','=',$logistic['logisticsOrderNo'])->first();
                                            if(empty($postage)){
                                                $new_postage = new PurchasePostageModel;
                                                $new_postage->purchase_order_id = $modellist['id'];
                                                $new_postage->post_coding       = $logistic['logisticsOrderNo'];
                                                $new_postage->user_id           = $user_id;
                                                $new_postage->save();
                                                $this->info('#Order:'.$modellist['id'].' add logisticsOrderNo :'. $logistic['logisticsOrderNo'].' insert success');
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }
        $this->info('finish.');
    }
}
