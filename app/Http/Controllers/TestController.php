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
use App\Models\WarehouseModel;
use DNS1D;
use App\Models\Channel\ChannelsModel;
use App\Models\Sellmore\ShipmentModel;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\CatalogModel;
use DB;
use Excel;
use App\Models\Message\ReplyModel;
use App\Jobs\Inorders;
use App\Modules\Channel\Adapter\AmazonAdapter;
use App\Models\Oversea\StockModel as fbaStock;
use App\Models\Order\ItemModel as orderItemss;
use App\Models\Message\Issues\AliexpressIssueListModel;
use App\Models\Message\Issues\AliexpressIssuesDetailModel;
use App\Models\Order\RefundModel;
use App\Models\Product\SupplierModel;

use Illuminate\Support\Facades\Storage;

use BarcodeGen;

class TestController extends Controller
{
    private $itemModel;

    public function __construct(OrderModel $orderModel, ItemModel $itemModel)
    {
        $this->itemModel = $itemModel;
    }

    // public function test2()
    // {
    //     // return Tool::barcodePrint('test1111');
    //     var_dump(base64_encode(serialize('你好')));
    // }

    public function test3()
    {
        var_dump('123');
        // $response = [
        //     'metas' => $this->metas(__FUNCTION__),
        // ];

        // return view('test', $response);
        $model = PackageModel::where('id', '<', 5);
        return redirect(route('package.index', ['outer_model' => $model]));
    }

    // public function test2()
    // {
    //     $package = PackageModel::find('17');

    //     $package->realTimeLogistics();
    // }
    // public function test2()
    // {
    //     $data['channel_ordernum'] = '1111';
    //     $data['ordernum'] = '3000';
    //     $data['channel_account_id'] = '365';
    //     $data['channel_id'] = '2';
    //     $data['status'] = 'PAID';
    //     $data['active'] = 'NORMAL';
    //     $data['items'][0]['sku'] = 'MPJ845D';
    //     $data['items'][0]['quantity'] = 1;
    //     $job = new Inorders($data);
    //     $job->onQueue('Inorders');
    //     $this->dispatch($job);
    // }

    public function test2()
    {
        $account = AccountModel::find(1);
        $single = new AmazonAdapter($account->api_config);
        // var_dump($single->requestReport());exit;
        // var_dump($single->getReportRequestList('53034017045'));exit;
        // $buf = $single->getReport('2724553088017044');
        var_dump(empty($single->listInShipment('FBA3VX2RL1')));
        // $arr = explode("\n", $buf);
        // $keys = explode("\t", $arr[0]);
        // $vals = [];
        // foreach($arr as $key => $value) {
        //     if(!$key) {
        //         continue;
        //     }
        //     $buf = explode("\t", $value);
        //     foreach($buf as $k => $v) {
        //         $vals[$keys[$k]] = $v;
        //     }
        //     var_dump($vals);
        //     var_dump($vals['afn-inbound-receiving-quantity']);exit;
            // var_dump($vals);exit;
            // $tmp = Tool::filter_sku($vals['sku']);
            // if(count($tmp)) {
            //     $item = ItemModel::where('sku', $tmp['0']['erpSku'])->first()
            //     if($item) {
            //         $vals['item_id'] = $item->id;
            //     }
            // }
            // $vals['title'] = $vals['product-name'];
            // $vals['channel_sku'] = $vals['sku'];
            // $vals['mfn_fulfillable_quantity'] = $vals['mfn-fulfillable-quantity'];
            // $vals['afn_warehouse_quantity'] = $vals['afn-warehouse-quantity'];
            // $vals['afn_fulfillable_quantity'] = $vals['afn-fulfillable-quantity'];
            // $vals['afn_unsellable_quantity'] = $vals['afn-unsellable-quantity'];
            // $vals['afn_reserved_quantity'] = $vals['afn-reserved-quantity'];
            // $vals['afn_total_quantity'] = $vals['afn-total-quantity'];
            // $vals['per_unit_volume'] = $vals['per-unit-volume'];
            // $vals['afn_inbound_working_quantity'] = $vals['afn-inbound-working-quantity'];
            // $vals['afn_inbound_shipped_quantity'] = $vals['afn-inbound-shipped-quantity'];
            // $vals['afn_inbound_receiving_quantity'] = $vals['afn-inbound-shipped-quantity'];
            // $vals['account_id'] = '1';
            // fbaStock::create($vals);
        // }exit;
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

    public function testYw(){
        $package = PackageModel::findOrFail(3);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
        ->getTracking($package);
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
         */
        foreach (AccountModel::all() as $account) {
            if($account->account == 'darli04@126.com'){ //测试diver

                //dd($account);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $messageList = $channel->getMessages();
                print_r($messageList);exit;

            }
        }


/*        $userId =  request()->user()->id;
        $accounts = AccountModel::where('customer_service_id','=',$userId)->get();
        if(count($accounts) <> 0){

            foreach ($accounts as $key => $account){
                $ids_ary[] = $account->id;
            }

            return $ids_ary;

        }
        exit;*/
    }

    public function testEbayCases(){
        foreach (AccountModel::all() as $account) {
            if($account->account == 'ebay@licn2011'){ //测试diver

                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $messageList = $channel->getCases();
                print_r($messageList);exit;

            }
        }
    }

    /*
     * 同步ebay信息
     */
    public function getEbayProduct(){
        $account = AccountModel::find(378);
        if ($account) {
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $is_do =true;
            $i=1;
            while($is_do) {
                $productList = $channel->getSellerEvents($i);
                exit;
                if ($productList) {
                    foreach($productList as $key=> $itemId){
                        $channel->getProductDetail($itemId);
                        if($key==10){
                            exit;
                        }
                    }
                    $i++;
                }else{
                    $is_do=false;
                }
            }

        }
    }
    public function getCurlData($remote_server)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $output;
    }
    public function getSmtIssue(){

      //  $refund = RefundModel::find(2);

/*        dd($refund->PakcageWeight);

        dd($refund);*/

        for($i = 1; $i>0; $i++){
            $url = 'http://v2.erp.moonarstore.com/admin/auto/getSuppliers/getSuppliersData?key=SLME5201314&page='.$i;
            $data = json_decode($this->getCurlData($url));
            if(empty($data)){
                break;
            }else{
                foreach ($data as $key => $value){
if($key == 1)
                    dd($value);

                    $img_src      = 'http://erp.moonarstore.com'.substr($value->attachment_url,1);
                    $content          = file_get_contents($img_src);
                    $suffix       = strstr(substr($value->attachment_url,1),'.');
                    $uploads_file = '/product/supplier/'.Tool::randString(16,false).$suffix;

                //dd($uploads_file);
                    $bytes = Storage::put($uploads_file,$content);
                    
                    $pay_type = $value->pay_method;

                    $insert = [
                        'company'         => $value->suppliers_company,
                        'address'         => $value->suppliers_address,
                        'contact_name'    => $value->suppliers_name,
                        //'contact_name' => $value->suppliers_phone,
                        'telephone'       => $value->suppliers_mobile,
                        'official_url'    => $value->suppliers_website,
                        'qq'              => $value->suppliers_qq,
                        'wangwang'        => $value->suppliers_wangwang,
                        'bank_account'    => $value->suppliers_bank,
                        'bank_code'       => $value->suppliers_card_number,
                        'examine_status'  => $value->suppliers_status,
                        'purchase_time'   => $value->supplierArrivalMinDays,
                        'created_by'      => $value->user_id,
                        'pay_type'        => isset(config('product.sellmore.pay_type')[$pay_type]) ? config('product.sellmore.pay_type')[$pay_type] : 'OTHER_PAY',
                        'qualifications'  => Tool::randString(16,false).$suffix,

                    ];

                    if(!empty($insert)){
                        SupplierModel::create($insert);
                    }
                }
            }
        }





        foreach (AccountModel::all() as $account) {
            if($account->account == 'smtjiahongming@126.com'){ //测试diver

                $channel = Channel::driver($account->channel->driver, $account->api_config);

                $getIssueLists = $channel->getIssues();
                if(!empty($getIssueLists)){
                    foreach($getIssueLists as $issue){
                        $issue_list = AliexpressIssueListModel::firstOrNew(['issue_id' => $issue['issue_id']]);
                        if(empty($issue_list->id)){
                            $issue_list->issue_id      = $issue['issue_id'];
                            $issue_list->gmtModified   = $issue['gmtModified'];
                            $issue_list->issueStatus   = $issue['issueStatus'];
                            $issue_list->gmtCreate     = $issue['gmtCreate'];
                            $issue_list->reasonChinese = $issue['reasonChinese'];
                            $issue_list->orderId       = $issue['orderId'];
                            $issue_list->reasonEnglish = $issue['reasonEnglish'];
                            $issue_list->issueType     = $issue['issueType'];
                            $issue_list->save();

                            if(!empty($issue['issue_detail'])){
                                $issue_detail = AliexpressIssuesDetailModel::firstOrNew(['issue_list_id' => $issue_list->id]);
                                if(empty($issue_detail->id)){
                                    $issue_detail->issue_list_id = $issue_list->id;
                                    $issue_detail->resultMemo = $issue['issue_detail']->resultMemo;
                                    $issue_detail->orderId = $issue['issue_detail']->resultObject->orderId;
                                    $issue_detail->gmtCreate = $issue['issue_detail']->resultObject->gmtCreate;
                                    $issue_detail->issueReasonId = $issue['issue_detail']->resultObject->issueReasonId;
                                    $issue_detail->buyerAliid = $issue['issue_detail']->resultObject->buyerAliid;
                                    $issue_detail->issueStatus = $issue['issue_detail']->resultObject->issueStatus;
                                    $issue_detail->issueReason = $issue['issue_detail']->resultObject->issueReason;
                                    $issue_detail->productName = $issue['issue_detail']->resultObject->productName;

                                    //序列化对象
                                    $issue_detail->productPrice = base64_encode(serialize($issue['issue_detail']->resultObject->productPrice));
                                    $issue_detail->buyerSolutionList = base64_encode(serialize($issue['issue_detail']->resultObject->buyerSolutionList));
                                    $issue_detail->sellerSolutionList = base64_encode(serialize($issue['issue_detail']->resultObject->sellerSolutionList));
                                    $issue_detail->platformSolutionList = base64_encode(serialize($issue['issue_detail']->resultObject->platformSolutionList));
                                    $issue_detail->refundMoneyMax = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMax));
                                    $issue_detail->refundMoneyMaxLocal = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMaxLocal));

                                    $issue_detail->save();

                                }

                                

                            }
                        }
                    }
                }
            }
        }
    }
}