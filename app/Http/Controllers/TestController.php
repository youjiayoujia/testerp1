<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */
namespace App\Http\Controllers;

header('Content-type: text/html; charset=UTF-8');

use App\Models\ChannelModel;
use App\Models\Message\MessageModel;
use Test;
use App\Models\Purchase\PurchaseOrderModel;
use Tool;
use Channel;
use Logistics;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;
use App\Modules\Paypal\PaypalApi;
use App\Models\Order\OrderPaypalDetailModel;

use App\Models\Publish\Ebay\EbayFeedBackModel;
use App\Models\Publish\Ebay\EbaySpecificsModel;


use App\Models\PackageModel;
use App\Models\ItemModel;
use App\Models\LogisticsModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use App\Modules\Channel\ChannelModule;
use App\Jobs\Job;
use App\Jobs\DoPackage;
use App\Jobs\SendMessages;

use App\Models\PickListModel;

use DNS1D;
use App\Models\Channel\ChannelsModel;
use App\Models\Sellmore\ShipmentModel;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\CatalogModel;
use DB;
use Excel;
use App\Models\Message\ReplyModel;


class TestController extends Controller
{
    private $itemModel;

    public function __construct(OrderModel $orderModel, ItemModel $itemModel)
    {
        $this->itemModel = $itemModel;
    }
    // public function test2()
    // {
    //     $item = ItemModel::find('1');var_dump($item->toArray());
    //     $item->out('8', 1, 'ADJUSTMENT', '1');exit;var_dump('ok');
    // }
    public function test2()
    {
        $rows[] = [
            '南京' => '上海',
            iconv('utf-8', 'gb2312', '武汉市') => iconv('utf-8', 'gb2312', '江苏省懂')
        ];
        $name = 'test';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            }, 'GB2312');
        })->download('XLS');
    }

    public function test1()
    {
        $shipment = ShipmentModel::where('shipmentID', '2')->first();
        var_dump($shipment->shipmentCarrierInfo);
        var_dump(unserialize($shipment->shipmentCarrierInfo));
    }

    public function index()
    {
        set_time_limit(0);
        $account = AccountModel::find(28);
        if ($account) {
            //初始化
            $i = 1;
            $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
            $endDate = date("Y-m-d H:i:s", time() - 300);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $nextToken = '';
            do {
                $start = microtime(true);
                $total = 0;
                $orderList = $channel->listOrders(
                    $startDate, //开始日期
                    $endDate, //截止日期
                    $account->api_status, //订单状态
                    $account->sync_pages, //每页数量
                    $nextToken //下一页TOKEN
                );
                foreach ($orderList['orders'] as $order) {
                    $order['channel_id'] = $account->channel->id;
                    $order['channel_account_id'] = $account->id;
                    $order['customer_service'] = $account->customer_service ? $account->customer_service->id : 0;
                    $order['operator'] = $account->operator ? $account->operator->id : 0;
                    $job = new InOrders($order);
                    $job = $job->onQueue('inOrders');
                    $this->dispatch($job);
                    $total++;
                }
                $nextToken = $orderList['nextToken'];
                //todo::Adapter->error()
                $result['status'] = 'success';
                $result['remark'] = 'Success.';
                $end = microtime(true);
                $lasting = round($end - $start, 3);
                echo $account->alias . ':' . $account->id . ' 抓取取第 ' . $i . ' 页, 耗时 ' . $lasting . ' 秒';
                $i++;
            } while ($nextToken);
        } else {
            echo 'Account is not exist.';
        }
    }

    public function testChinaPost()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }

    public function testWinit()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }

    public function test4px()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }

    public function testSmt()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->createWarehouseOrder($package);
        exit;
    }

    public function aliexpressOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(2);
        $startDate = date("Y-m-d H:i:s", strtotime('-30 day'));
        $endDate = date("Y-m-d H:i:s", strtotime('-12 hours'));
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        foreach ($status as $orderStatus) {
            $pageTotalNum = 1;
            $pageSize = 50;
            for ($i = 1; $i <= $pageTotalNum; $i++) {
                $orderList = $channel->listOrdersOther($startDate, $endDate, $orderStatus, $i, $pageSize);
                if (isset($orderList['orderList'])) {
                    if ($i == 1) {
                        $pageTotalNum = ceil($orderList['totalItem'] / $pageSize); //重新生成总页数
                    }
                    foreach ($orderList['orderList'] as $list) {
                        $thisOrder = $this->orderModel->where('channel_ordernum', $list['orderId'])->first();
                        if ($thisOrder) {
                            continue;
                        }
                        $orderDetail = $channel->getOrder($list['orderId']);
                        if (isset($orderDetail['orderStatus'])) {
                            $order = $channel->parseOrder($list, $orderDetail);
                            if ($order) {
                                $thisOrder = $this->orderModel->where('channel_ordernum',
                                    $order['channel_ordernum'])->first();
                                $order['channel_id'] = $account->channel->id;
                                $order['channel_account_id'] = $account->id;
                                if ($thisOrder) {
                                    $thisOrder->updateOrder($order);
                                } else {
                                    $this->orderModel->createOrder($order);
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                } else {
                    break;
                }
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function lazadaOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(4);
        $startDate = date("Y-m-d H:i:s", strtotime('-1 day'));
        $endDate = date("Y-m-d H:i:s");
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $orderList = $channel->listOrders($startDate, $endDate, $status, 10);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            $order['channel_id'] = $account->channel->id;
            $order['channel_account_id'] = $account->id;
            if ($thisOrder) {
                //$thisOrder->updateOrder($order);
            } else {
                $this->orderModel->createOrder($order);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function cdiscountOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(10);
        $startDate = date("Y-m-d H:i:s", strtotime('-1 day'));
        $endDate = date("Y-m-d H:i:s");
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $orderList = $channel->listOrders($startDate, $endDate, $status);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            $order['channel_id'] = $account->channel->id;
            $order['channel_account_id'] = $account->id;
            if ($thisOrder) {
                //$thisOrder->updateOrder($order);
            } else {
                $this->orderModel->createOrder($order);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function test()
    {
        $datas = DB::table('test')->get();
        foreach ($datas as $data) {
            $spu_id = DB::table('spus')->insertGetId(
                array(
                    'spu' => $data->sku,
                    'status' => 0,
                    'created_at' => '2015-10-16 16:33:00',
                    'updated_at' => '2015-10-16 16:33:00'
                )
            );
            $product_id = DB::table('products')->insertGetId(
                array(
                    'spu_id' => $spu_id,
                    'name' => $data->title,
                    'c_name' => $data->c_name,
                    'model' => $data->sku,
                    'weight' => $data->weight,
                    'catalog_id' => 1,
                    'supplier_id' => 1,
                    'warehouse_id' => 1,
                    'default_image' => 0,
                    'status' => 1
                )
            );
            $sku_id = DB::table('items')->insertGetId(
                array(
                    'product_id' => $product_id,
                    'name' => $data->title,
                    'c_name' => $data->c_name,
                    'sku' => $data->sku,
                    'weight' => $data->weight,
                    'purchase_price' => $data->value,
                    'warehouse_position' => $data->location,
                    'warehouse_id' => 1,
                    'catalog_id' => 1,
                    'supplier_id' => 1,
                    'status' => 1
                )
            );
        }
    }

    public function getWishProduct()
    {
        $accountID = request()->get('id');
        $begin = microtime(true);
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $hasProduct = true;
        $start = 0;
        while ($hasProduct) {
            $productList = $channel->getOnlineProduct($start, 500);
            if ($productList) {
                foreach ($productList as $product) {
                    $is_add = true;
                    $productInfo = $product['productInfo'];
                    $variants = $product['variants'];
                    foreach ($variants as $key => $variant) {
                        $productInfo['sellerID'] = $variant['sellerID']; //这个随便保存一个就好
                        $variants[$key]['account_id'] = $accountID;
                    }
                    $productInfo['account_id'] = $accountID;
                    $thisProduct = WishPublishProductModel::where('productID', $productInfo['productID'])->first();
                    if ($thisProduct) {
                        $is_add = false;
                        $mark_id = $thisProduct->id;
                    }
                    if ($is_add) {
                        $wish = WishPublishProductModel::create($productInfo);
                        foreach ($variants as $detail) {
                            $detail['product_id'] = $wish->id;
                            $wishDetail = WishPublishProductDetailModel::create($detail);
                        }
                    } else {
                        WishPublishProductModel::where('productID', $productInfo['productID'])->update($productInfo);
                        foreach ($variants as $key1 => $item) {
                            $productDetail = WishPublishProductModel::find($mark_id)->details;
                            if (count($variants) == count($productDetail)) {
                                foreach ($productDetail as $key2 => $productItem) {
                                    if ($key1 == $key2) {
                                        $productItem->update($item);
                                    }
                                }
                            } else {
                                foreach ($productDetail as $key2 => $orderItem) {
                                    $orderItem->delete($item);
                                }
                                foreach ($variants as $value) {
                                    $value['product_id'] = $mark_id;
                                    WishPublishProductDetailModel::create($value);
                                }
                            }
                        }
                    }
                }
                $start++;
            } else {
                $hasProduct = false;
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }

    public function getEbayInfo()
    {
        $accountID = request()->get('id');
        $begin = microtime(true);
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbaySite();
    }

    public function testLazada()
    {


        $accountId = 201;
        $account = AccountModel::findOrFail($accountId);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $startDate = date('Y-m-d', strtotime('-2 day'));
        $page = 0;
        $is_do = true;
        do {
            $result = $channel->getChangedOrders($startDate, $page, $pageSize = 500);
            if ($result) {
                $page++;
                foreach ($result as $re) {
                    if ($re['Order']['state'] == 'REFUNDED') { //退款状态
                        var_dump($re);
                    }

                }
            } else {
                $is_do = false;
            }
        } while ($is_do);


        /*  $result = $channel->GetFeedback();
          foreach($result as $re){
              $re['channel_account_id'] = $accountId;
              $feedback = EbayFeedBackModel::where(['feedback_id'=>$re['feedback_id'],'channel_account_id'=>$accountId])->first();
              if(empty($feedback)){
                  echo 11;
                  EbayFeedBackModel::create($re);
              }
          }*/

        $packages = PackageModel::where('order_id', 12914)->get();
        foreach ($packages as $package) {
            $OrderItemIds = [];
            foreach ($package->items as $item) {
                $temp = $item->orderItem->transaction_id;
                $temp = explode(',', $temp);
                foreach ($temp as $v) {
                    $v_temp = explode('@', $v);
                    $OrderItemIds[] = $v_temp[0];
                }
            }
            /* $OrderItemIds = [
                 9047009, 9047011
             ];*/
            $channel_listnum[] = $package->order->channel_listnum;
            $account = AccountModel::findOrFail($package->channel_account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->getPackageId(implode(',', $channel_listnum));
            if ($result) {
                if (isset($result[$OrderItemIds[0]])) { // 获取到了 最踪号 和 PackageId
                    $update_info = [
                        'tracking_no' => $result[$OrderItemIds[0]]['TrackingCode'],
                        'lazada_package_id' => $result[$OrderItemIds[0]]['PackageId'],
                    ];
                    $package->update($update_info);
                } else { //特殊情况数据记录
                }
            } else { //api调用失败
            }
            var_dump($OrderItemIds);
            var_dump($result);
        }

        exit;
    }

    public function testPaypal()
    {
        $orders = OrderModel::where('id', 12851)->get();
        foreach ($orders as $order) {
            $is_paypals = false;
            //$erp_country      = trim($order->shipping_country);
            $erp_country_code = trim($order->shipping_country);
            $erp_state = trim($order->shipping_state);
            $erp_city = trim($order->shipping_city);
            $erp_address = trim($order->shipping_address);
            $erp_address_1 = trim($order->shipping_address1);
            $erp_address = trim($erp_address . $erp_address_1);
            $erp_address = str_replace(' ', '', $erp_address); //把地址信息中的空格都去掉
            $erp_name = trim($order->shipping_firstname . $order->shipping_lastname);
            $erp_zip = trim($order->shipping_zipcode);
            $error = array();
            $paypals = $order->channelAccount->paypal;
            foreach ($paypals as $paypal) {
                $api = new  PaypalApi($paypal);
                $result = $api->apiRequest('gettransactionDetails', $order->transaction_number);
                $transactionInfo = $api->httpResponse;
                if ($result && $transactionInfo != null && (strtoupper($transactionInfo ['ACK']) == 'SUCCESS' || strtoupper($transactionInfo ['ACK']) == 'SUCCESSWITHWARNING')) {
                    $is_paypals = true;
                    $tInfo = $transactionInfo;
                    $paypal_account = isset($tInfo ['EMAIL']) ? $tInfo ['EMAIL'] : '';
                    $paypal_buyer_name = trim($tInfo ['SHIPTONAME']);
                    $paypal_country_code = trim($tInfo['SHIPTOCOUNTRYCODE']); //国家简称
                    $paypal_country = trim($tInfo['SHIPTOCOUNTRYNAME']); //国家
                    $paypal_city = trim($tInfo['SHIPTOCITY']);        //城市
                    $paypal_state = trim($tInfo['SHIPTOSTATE']);       //州
                    $paypal_street = trim($tInfo['SHIPTOSTREET']);      //街道1
                    $paypal_street2 = trim($tInfo['SHIPTOSTREET2']);     //街道2
                    $paypal_zip = trim($tInfo['SHIPTOZIP']);         //邮编
                    $paypal_phone = isset($tInfo['SHIPTOPHONENUM']) ? trim($tInfo['SHIPTOPHONENUM']) : '';    //电话
                    $paypalAddress = $paypal_street . ' ' . $paypal_street2 . ' ' . $paypal_city . ' ' . $paypal_state . ' ' . $paypal_country . '(' . $paypal_country_code . ') ' . $paypal_zip;
                    if (strtoupper($erp_country_code) != strtoupper($paypal_country_code)) {
                        $error[] = '国家不一致';
                    }
                    //把paypal的信息记录
                    $is_exist = OrderPaypalDetailModel::where('order_id', $order->id)->first();
                    if (empty($is_exist)) {
                        $add = [
                            'order_id' => $order->id,
                            'paypal_account' => $paypal_account,
                            'paypal_buyer_name' => $paypal_buyer_name,
                            'paypal_address' => $paypalAddress,
                            'paypal_country' => $paypal_country_code
                        ];
                        OrderPaypalDetailModel::create($add);
                    }
                    if (!empty($error)) { //设置为匹配失败
                        $order->update(['order_is_alert' => 2]);
                        $order->remark('paypal匹配失败:' . implode(',', $error));
                    } else { //设置为匹配成功
                        $order->update(['order_is_alert' => 3]);
                        $order->remark('paypal匹配成功');
                        //remarks
                    }
                    break;
                }
            }
            if (!$is_paypals) { //说明对应的paypal 都没有找到信息
                $order->update(['order_is_alert' => 2]);
                $order->remark('paypal匹配失败:当前交易凭证在预设的PayPal组中，未查询到交易详情，请通过其它方式查询');
            }
        }
    }
    public function jdtestCrm(){
        /*        $message_obj = MessageModel::find(36336);
                //$tt = $message_obj->ChannelMessageFields();

                dd($message_obj->MessageFields);exit;
                exit;*/


        //渠道测试块

        /*        $message_obj = MessageModel::find(36259);
                $fields = unserialize(base64_decode($message_obj->channel_message_fields));
                dd($fields);exit;*/

        /*
                 $reply_obj = ReplyModel::find(28569);

                  foreach (AccountModel::all() as $account) {
                    if( $account->account == 'wintrade9'){ //测试diver

                        $channel = Channel::driver($account->channel->driver, $account->api_config);
                        $messageList = $channel->sendMessages($reply_obj);
                        print_r($messageList);exit;

                    }
                }*/
        /*
         *
         *
         */        foreach (AccountModel::all() as $account) {
            if($account->account == 'jdtest@ebay.com'){ //测试diver

                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $messageList = $channel->getCases();
                print_r($messageList);exit;

            }
        }
    }
}