<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;


use App\Models\ChannelModel;
use Test;

use App\Models\Purchase\PurchaseOrderModel;


use Tool;
use Channel;
use App\Modules\Paypal\PaypalApi;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;
use App\Models\Order\OrderPaypalDetailModel;

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

use DNS1D;
use App\Models\Channel\ChannelsModel;
use App\Models\Sellmore\ShipmentModel;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\CatalogModel;
use DB;

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
        $package = PackageModel::find(98);
        $package->createPackageItems();
    }

    public function test1()
    {
        $shipment = ShipmentModel::where('shipmentID', '2')->first();
        var_dump($shipment->shipmentCarrierInfo);
        var_dump(unserialize($shipment->shipmentCarrierInfo));
    }

    public function index()
    {
        $data = CatalogModel::all()->channels;

        dd($data);
        exit;
        /*        $dataaaa = $reply->message->account->toArray();
                var_dump($dataaaa);exit;*/

        /*        $job = new SendMessages($reply);
                $job = $job->onQueue('SendMessages');
                $this->dispatch($job);

                exit;*/


        $package = PackageModel::find(request()->input('id'));
        $package->assignLogistics();
        $job = new PlaceLogistics($package);
        $job = $job->onQueue('placeLogistics');
        $this->dispatch($job);
        exit;
        $orderModel = new OrderModel;
        $start = microtime(true);
        $account = AccountModel::find(request()->input('id'));
        if ($account) {
            $i = 1;
            $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
            $endDate = date("Y-m-d H:i:s", time() - 300);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $nextToken = '';
            do {
                $start = microtime(true);
                $total = 0;
                $commandLog = CommandLog::create([
                    'relation_id' => $account->id,
                    'signature' => __CLASS__,
                    'description' => 'get orders form ' . $account->channel->name . ':' . $account->alias . '[' . $account->id . '] - ' . $i . '.',
                    'lasting' => 0,
                    'total' => 0,
                    'result' => 'init',
                    'remark' => 'init',
                ]);
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
                $commandLog->update([
                    'data' => serialize($orderList['orders']),
                    'lasting' => $lasting,
                    'total' => $total,
                    'result' => $result['status'],
                    'remark' => $result['remark'],
                ]);
                echo $account->alias . ':' . $account->id . ' 抓取取第 ' . $i . ' 页, 耗时 ' . $lasting . ' 秒' . '<br>';
            } while ($nextToken);
        }
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

    public function testReturnTrack()
    {
        $driver = request()->get('driver');
        $account_id = request()->get('account_id');
        $orderModel = new OrderModel;
        $channel_id = ChannelModel::where('driver', $driver)->first()->id;

        if ($driver == 'amazon') {

        } elseif ($driver == 'aliexpress') {
            $packages = PackageModel::where('is_mark', 0)->where('order_id', 2650)->whereHas('order', function ($query) {
                $query = $query->where('orders.create_time', '>=', '2016-07-03');
            })->get();
            foreach ($packages as $package) {
                $package_items = $package->items;
                $remark = '';
                $is_success = true;
                $item_array = [];

                //先判断订单状态
                $order = OrderModel::where('id', $package->order_id)->first();
                $account = AccountModel::findOrFail($order->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $order_status = $channel->getOrder($order->channel_ordernum);
                $order_status['orderStatus'] = 'SELLER_PART_SEND_GOODS';
                if (isset($order_status['orderStatus']) && $order_status['orderStatus'] == "WAIT_BUYER_ACCEPT_GOODS") {
                    //已经处于买家收货状态， 不需标记发货
                    $result['status'] = true;
                    $result['info'] = '平台状态为等待买家收货';

                } elseif (isset($order_status['orderStatus']) && ($order_status['orderStatus'] == "WAIT_SELLER_SEND_GOODS" || $order_status['orderStatus'] == "SELLER_PART_SEND_GOODS")) {

                    foreach ($package_items as $item) {
                        $item_array[$item->order_item_id] = $item->order_item_id;
                    }
                    $order_item_count = $order->items->count();

                    $tracking_info = [
                        'serviceName' => 'test',
                        'logisticsNo' => $package->tracking_no,
                        'description' => '',
                        'trackingWebsite' => '',
                    ];
                    if (count($item_array) == $order_item_count) { //包裹item 的sku种类数目==订单item的sku种类数目  意味着没有拆分订单
                        $tracking_info['sendType'] = 'all';
                    } else { //数量不等
                        if ($order_status['orderStatus'] == "WAIT_SELLER_SEND_GOODS") { //说明没有进行标记发货。 sendType = part
                            $tracking_info['sendType'] = 'part';
                        } else { //这个已经部分发货了。 但是要确定 这次还是部分发货  sendType = part  或者 是最后一次发货 sendType = all 那么查找这个订单 已经标记发货了的包裹 sku种类相加
                            $is_mark_item = [];
                            $is_mark_packages = PackageModel::where('is_mark', 1)->where('order_id', $package->order_id)->get();
                            foreach ($is_mark_packages as $is_mark_package) {
                                foreach ($is_mark_package->items as $item) {
                                    $is_mark_item[$item->order_item_id] = $item->order_item_id;
                                }
                            }
                            if (count($is_mark_item) + count($item_array) == $order_item_count) { //已经标记数量+本次标记数量 = 总数量 sendType = all
                                $tracking_info['sendType'] = 'all';
                            } else {
                                $tracking_info['sendType'] = 'part';
                            }
                        }
                    }
                    $tracking_info['outRef'] = $order->channel_ordernum;
                    $result = $channel->returnTrack($tracking_info);

                } else {

                    $result['status'] = false;
                    $result['info'] = '未知错误' . var_export($order_status, true);

                }


                if ($result['status']) {
                    PackageModel::where('id', $package->id)->update(array(
                        'is_mark' => 1,
                        'is_upload' => 1,
                    ));
                }


            }
        } elseif ($driver == 'wish') {
            $packages = PackageModel::where('is_mark', 0)->whereHas('order', function ($query) {
                $query = $query->where('orders.create_time', '>=', '2016-07-03');
            })->get();
            foreach ($packages as $package) {
                $package_items = $package->items;
                $remark = '';
                $is_success = true;
                foreach ($package_items as $item) {
                    $channel_order_id = ItemModel::where('id', $item->order_item_id)->first()->channel_order_id;
                    $tracking_info = [
                        'id' => $channel_order_id,
                        'tracking_number' => $package->tracking_no,
                        'tracking_provider' => 'testtt',
                        'ship_note' => '',
                    ];
                    $account = AccountModel::findOrFail($package->channel_account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $result = $channel->returnTrack($tracking_info);
                    if ($result['status']) {
                        $remark = $remark . $channel_order_id . $result['info'] . ' ';
                    } else {
                        $is_success = false;
                        $remark = $remark . $channel_order_id . $result['info'] . ' ';
                    }
                }
                if ($is_success) {
                    PackageModel::where('id', $package->id)->update(array(
                        'is_mark' => 1
                    ));
                }
                //var_dump($package);
            }
        } elseif ($driver == 'ebay') {
            $packages = PackageModel::where(['channel_id' => $channel_id, 'is_mark' => '0'])->where('tracking_no', '!=', '')->whereHas('order', function ($query) {
                $query = $query->where('orders.created_at', '>=', '2016-07-03');
            })->get();
            foreach ($packages as $package) {
                $package_items = $package->items;
                $remark = '';
                $is_success = true;
                $logistics_channel_name = ChannelNameModel::where('channel_id', $package->channel_id)->whereHas('logistics', function ($query) use ($package) {
                    $query = $query->where('logistics_id', $package->logistics_id);
                })->first()->name;

                foreach ($package_items as $item) {
                    $order_item = ItemModel::where('id', $item->order_item_id)->first();
                    $tracking_info = [
                        'IsUploadTrackingNumber' => true, //true or false
                        'ShipmentTrackingNumber' => $package->tracking_no, //追踪号
                        'ShippingCarrierUsed' => $logistics_channel_name,//承运商
                        'ShippedTime' => date('Y-m-d\TH:i:s\Z', time()), //发货时间 date('Y-m-d\TH:i:s\Z')
                        'ItemID' => $order_item->orders_item_number, //商品id
                        'TransactionID' => !empty($order_item->transaction_id) ? $order_item->transaction_id : 0
                    ];
                    $account = AccountModel::findOrFail($package->channel_account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $result = $channel->returnTrack($tracking_info);
                    if ($result['status']) {
                        $remark = $remark . $result['info'] . ' ';
                    } else {
                        $is_success = false;
                        $remark = $remark . $result['info'] . ' ';
                    }
                }
                if ($is_success) {
                    PackageModel::where('id', $package->id)->update(array(
                        'is_mark' => 1
                    ));
                }

            }

            exit;


        } elseif ($driver == 'lazada') {

            $packages = PackageModel::where('is_mark', 0)->where('order_id', 2685)->whereHas('order', function ($query) {
                $query = $query->where('orders.create_time', '>=', '2016-07-03');
            })->get();

            foreach ($packages as $package) {
                $package_items = $package->items;
                $order = OrderModel::where('id', $package->order_id)->first();
                $remark = '';
                $is_success = true;
                $OrderItemIds = [];
                foreach ($package_items as $item) {
                    $temp = ItemModel::where('id', $item->order_item_id)->first()->transaction_id;
                    $temp = explode(',', $temp);
                    foreach ($temp as $v) {
                        $v_temp = explode('@', $v);
                        $OrderItemIds[] = $v_temp[0];
                    }

                }
                $OrderItemIds = array_unique($OrderItemIds);

                $tracking_info = [];
                $tracking_info['TrackingNumber'] = '';
                $tracking_info['ShippingProvider'] = 'AS-Poslaju';
                $tracking_info['OrderItemIds'] = implode(',', $OrderItemIds);
            }
            $account = AccountModel::findOrFail($package->channel_account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->returnTrack($tracking_info);


        } elseif ($driver == 'cdiscount') {

            $packages = PackageModel::where('is_mark', 0)->where('order_id', 2633)->whereHas('order', function ($query) {
                $query = $query->where('orders.create_time', '>=', '2016-07-03');
            })->get();

            foreach ($packages as $package) {
                $package_items = $package->items;
                $order = OrderModel::where('id', $package->order_id)->first();
                $remark = '';
                $is_success = true;
                $productsArr = [];
                foreach ($package_items as $item) {
                    $productsArr[] = ItemModel::where('id', $item->order_item_id)->first()->channel_sku;

                }

                $tracking_info = [];
                $tracking_info['OrderNumber'] = $order->channel_ordernum;
                $tracking_info['TrackingNumber'] = $package->tracking_no;
                $tracking_info['TrackingUrl'] = '';
                $tracking_info['CarrierName'] = '';
                $tracking_info['products_info'] = $productsArr;

                $account = AccountModel::findOrFail($package->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $result = $channel->returnTrack($tracking_info);
                if ($result['status']) {
                    PackageModel::where('id', $package->id)->update(array(
                        'is_mark' => 1
                    ));
                }
            }


        } else {
            echo '输入参数错误';
            exit;
        }


        //$result =  $orderModel->where('channel_ordernum','122015019019-1655048371002')->get();
        //  $result =  $orderModel->with('items')->where('channel_sku','352*E3510A3')->get();

        /*    $result = OrderModel::with(['items' => function ($query) {
                $query->where('channel_sku', '352*E3510A3')->where('order_id',2592);
            }])->get();



            var_dump($result);exit;*/


        exit;

    }

    public function testLazada()
    {
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
                        'tracking_no'=>$result[$OrderItemIds[0]]['TrackingCode'],
                        'lazada_package_id'=>$result[$OrderItemIds[0]]['PackageId'],
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
                if ($result && $transactionInfo != NULL && (strtoupper($transactionInfo ['ACK']) == 'SUCCESS' || strtoupper($transactionInfo ['ACK']) == 'SUCCESSWITHWARNING')) {
                    $is_paypals = true;
                    $tInfo = $transactionInfo;
                    $paypal_account=isset($tInfo ['EMAIL'])?$tInfo ['EMAIL']:'';
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
                            'paypal_buyer_name'=>$paypal_buyer_name,
                            'paypal_address'=>$paypalAddress,
                            'paypal_country'=>$paypal_country_code
                        ];
                        OrderPaypalDetailModel::create($add);

                    }


                    if (!empty($error)) { //设置为匹配失败
                        $order->update(['order_is_alert'=>2]);
                        $order->remark('paypal匹配失败:'.implode(',',$error));


                    } else { //设置为匹配成功
                        $order->update(['order_is_alert'=>3]);
                        $order->remark('paypal匹配成功');
                        //remarks

                    }
                    break;
                }
            }
            if (!$is_paypals) { //说明对应的paypal 都没有找到信息

                $order->update(['order_is_alert'=>2]);
                $order->remark('paypal匹配失败:当前交易凭证在预设的PayPal组中，未查询到交易详情，请通过其它方式查询');
            }
        }


    }
}