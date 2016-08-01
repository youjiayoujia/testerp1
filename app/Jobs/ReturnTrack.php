<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PackageModel;
use App\Models\Channel\AccountModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Package\ItemModel;
use App\Models\OrderModel;

use Tool;
use Channel;




class ReturnTrack extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package)
    {
        //
        $this->package = $package;
        $this->description = ' Info:[ order_id '  . $this->package['order_id'] . ': package_id ' . $this->package['id'] . '] start mark.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PackageModel $packageModel)
    {
        $start = microtime(true);

        $driver = AccountModel::find($this->package->channel_account_id)->channel->driver;


        $package =$this->package;

        $package_items =  $package->items;

        $remark ='';
        $is_success = true;

        $logistics_channel_name = ChannelNameModel::where('channel_id', $package->channel_id)->whereHas('logistics', function ($query) use($package)  {
            $query = $query->where('logistics_id',$package->logistics_id);
        })->first()->name;

        if(!empty($logistics_channel_name)){
            if($driver=='amazon'){

            }elseif($driver=='aliexpress'){
                $package_items = $package->items;
                $remark = '';
                $is_success = true;
                $item_array =[];
                //先判断订单状态
                $order  = OrderModel::where('id',$package->order_id)->first();
                $account = AccountModel::findOrFail($order->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $order_status = $channel->getOrder($order->channel_ordernum);

                $order_status['orderStatus'] = 'WAIT_SELLER_SEND_GOODS';
                if(isset($order_status['orderStatus'])&& $order_status['orderStatus']== "WAIT_BUYER_ACCEPT_GOODS"){
                    //已经处于买家收货状态， 不需标记发货

                    $remark  ='平台状态为等待买家收货,因此变成已标记';


                }elseif(isset($order_status['orderStatus'])&&($order_status['orderStatus']== "WAIT_SELLER_SEND_GOODS" || $order_status['orderStatus']== "SELLER_PART_SEND_GOODS")){

                    foreach($package_items as $item){
                        $item_array[$item->order_item_id] = $item->order_item_id;
                    }
                    $order_item_count = $order->items->count();

                    $tracking_info =[
                        'serviceName'=>$logistics_channel_name,
                        'logisticsNo'=>$package->tracking_no,
                        'description'=>'Tracking website: '.$package->tracking_link,
                        'trackingWebsite'=>$package->tracking_link,
                    ];


                    if(count($item_array) ==$order_item_count ){ //包裹item 的sku种类数目==订单item的sku种类数目  意味着没有拆分订单
                        $tracking_info['sendType']='all';
                    }else{ //数量不等
                        if($order_status['orderStatus']== "WAIT_SELLER_SEND_GOODS"){ //说明没有进行标记发货。 sendType = part
                            $tracking_info['sendType']='part';
                        }else{ //这个已经部分发货了。 但是要确定 这次还是部分发货  sendType = part  或者 是最后一次发货 sendType = all 那么查找这个订单 已经标记发货了的包裹 sku种类相加
                            $is_mark_item = [];
                            $is_mark_packages =     PackageModel::where('is_mark', 1 )->where('order_id',$package->order_id)->get();
                            foreach($is_mark_packages as $is_mark_package ){
                                foreach($is_mark_package->items as $item){
                                    $is_mark_item[$item->order_item_id] = $item->order_item_id;
                                }
                            }
                            if(count($is_mark_item)+count($item_array)==$order_item_count){ //已经标记数量+本次标记数量 = 总数量 sendType = all
                                $tracking_info['sendType']='all';
                            }else{
                                $tracking_info['sendType']='part';
                            }
                        }
                    }
                    $tracking_info['outRef'] = $order->channel_ordernum;
                    $result = $channel->returnTrack($tracking_info);
                    if(!$result['status']){
                        $is_success = false;
                    }
                    $remark = $result['info'];

                }else{
                    $remark  ='未知错误'.var_export($order_status,true);
                }

                if($is_success){
                    ItemModel::where('package_id',$package->id)->update(array(
                        'is_mark'=>1,
                        'is_upload'=>1
                    ));

                    PackageModel::where('id',$package->id)->update(array(
                        'is_mark' =>1,
                        'is_upload' =>1
                    ));


                }
            }elseif($driver=='wish'){
                foreach($package_items as $item){
                    if($item->is_mark=='1'){ //已经标记过了
                        continue;
                    }
                    $order_item = $item->orderItem;
                    $channel_order_id = $order_item->channel_order_id;
                    $tracking_info =[
                        'id' => $channel_order_id,
                        'tracking_number' =>$package->tracking_no,
                        'tracking_provider' =>$logistics_channel_name,
                        'ship_note' =>'',
                    ];
                    $account = AccountModel::findOrFail($package->channel_account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $result = $channel->returnTrack($tracking_info);
                    if($result['status']){

                        ItemModel::where('id',$item->id)->update(array(
                            'is_mark'=>1,
                            'is_upload'=>1,
                        ));
                        $remark =$remark.$channel_order_id.$result['info'].' ';
                    }else{
                        $is_success = false;
                        $remark =$remark.$channel_order_id.$result['info'].' ';
                    }
                }

                if($is_success){
                    PackageModel::where('id',$package->id)->update(array(
                        'is_mark' =>1,
                        'is_upload'=>1,
                    ));
                }
            }elseif($driver=='ebay'){
                foreach($package_items as $item){

                  //  $order_item = ItemModel::where('id',$item->order_item_id)->first();

                    if($item->is_mark=='1'){ //已经标记过了
                       continue;
                    }
                    $order_item = $item->orderItem;
                    $tracking_info =[
                        'IsUploadTrackingNumber' =>true, //true or false
                        'ShipmentTrackingNumber'=>$package->tracking_no, //追踪号
                        'ShippingCarrierUsed'=>$logistics_channel_name,//承运商
                        'ShippedTime' =>date('Y-m-d\TH:i:s\Z',time()), //发货时间 date('Y-m-d\TH:i:s\Z')
                        'ItemID' =>$order_item->orders_item_number, //商品id
                        'TransactionID' =>!empty($order_item->transaction_id)?$order_item->transaction_id:0
                    ];
                    $account = AccountModel::findOrFail($package->channel_account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $result = $channel->returnTrack($tracking_info);
                    if($result['status']){
                        $remark =$remark.$result['info'].' ';
                        ItemModel::where('id',$item->id)->update(array(
                            'is_mark'=>1,
                            'is_upload'=>1
                        ));

                    }else{
                        $is_success = false;
                        $remark =$remark.$result['info'].' ';
                    }

                }
                if($is_success){
                    PackageModel::where('id',$package->id)->update(array(
                        'is_mark' =>1,
                        'is_upload' =>1
                    ));
                }
            }elseif($driver=='lazada'){
                $OrderItemIds = [];
                foreach ($package_items as $item) {
                    $order_item = $item->orderItem;
                    $temp = $order_item->transaction_id;
                    $temp =explode(',',$temp);
                    foreach($temp as $v){
                        $v_temp = explode('@',$v);
                        $OrderItemIds[] = $v_temp[0];
                    }

                }
                $OrderItemIds = array_unique($OrderItemIds);

                $tracking_info   = [
                    'TrackingNumber' =>'',
                    'ShippingProvider'=>$logistics_channel_name,
                    'OrderItemIds'=>implode(',',$OrderItemIds)
                ];
                $account = AccountModel::findOrFail($package->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $result = $channel->returnTrack($tracking_info);
                if($result['status']){

                    ItemModel::where('package_id',$package->id)->update(array(
                        'is_mark'=>1,
                        'is_upload'=>1
                    ));

                    $remark = $result['info'];
                }else{
                    $is_success = false;
                    $remark = $result['info'];
                }

                if($is_success){
                    PackageModel::where('id',$package->id)->update(array(
                        'is_mark' =>1,
                        'is_upload' =>1
                    ));
                }

            }elseif($driver=='cdiscount'){
                $order  = OrderModel::where('id',$package->order_id)->first();

                $productsArr =[];
                foreach ($package_items as $item) {
                    $order_item = $item->orderItem;
                    $productsArr[] = $order_item->channel_sku;
                }

                $tracking_info   = [
                    'OrderNumber'=>$order->channel_ordernum,
                    'TrackingNumber'=>$package->tracking_no,
                    'TrackingUrl'=>$package->tracking_link,
                    'CarrierName'=>$logistics_channel_name,
                    'products_info'=>$productsArr
                ];

                $account = AccountModel::findOrFail($package->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $result = $channel->returnTrack($tracking_info);
                if($result['status']){

                    ItemModel::where('package_id',$package->id)->update(array(
                        'is_mark'=>1,
                        'is_upload'=>1
                    ));
                    $remark = $result['info'];

                }else{
                    $is_success =false;
                    $remark = $result['info'];

                }
                if($is_success){
                    PackageModel::where('id',$package->id)->update(array(
                        'is_mark' =>1,
                        'is_upload' =>1
                    ));
                }


            }else{
                echo 'error!';
            }


            if($is_success){
                $this->result['status'] = 'success';
            }else{
                $this->result['status'] = 'fail';

            }
            $this->result['remark'] = $remark;

        }else{

            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Could not find logistics_channel_name.';
        }



        $this->relation_id = $this->package->order_id;

        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('ReturnTrack', json_encode($this->package));












    }
}