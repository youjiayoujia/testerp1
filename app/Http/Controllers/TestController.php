<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;


use Test;

use App\Models\Purchase\PurchaseOrderModel;
use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;

use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use App\Models\ItemModel;
use App\Modules\Channel\ChannelModule;
use App\Models\PackageModel;
use App\Jobs\DoPackage;
use DNS1D;
use App\Http\Controllers\Controller;
use App\Models\CurrencyModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Channel\ChannelsModel;
use App\Models\Message\ReplyModel;
use App\Models\Message\MessageModel;


use DB;

class TestController extends Controller
{
    private $itemModel;

    public function __construct(OrderModel $orderModel, ItemModel $itemModel)
    {
        $this->itemModel = $itemModel;
    }

    public function test1()
    {
        $url = 'http://baidu.com/index.php?a=1&b=3';
        $arr = pathinfo($url);
        var_dump($arr);
        exit;
        echo Tool::barcodePrint('67');
    }

    public function index()
    {

        $accounts = AccountModel::all();

        foreach ($accounts as $account) {
            if ($account->channel->driver == 'amazon') {
                $channel = Channel::driver($account->channel->driver, $account->api_config);

                $messageList = $channel->getMessages();


                foreach ($messageList as $message) {
                    var_dump($message->id);exit;

                   // $messageNew = MessageModel::where(['message_id' => $message->id])->get();
                    var_dump($messageNew);
                    if ($messageNew->id == null) {
                        $messageNew->account_id = $account->id;
                        $messageNew->message_id = $message['message_id'];
                        $messageNew->from_name = $message['from_name'];
/*                        $messageNew->labels = $message['labels'];*/
                        $messageNew->label = $message['label'];
                        $messageNew->from = $message['from'];
                        $messageNew->to = $message['to'];
                        $messageNew->date = $message['date'];
                        $messageNew->subject = $message['subject'];
                        $messageNew->content = $message['content'];
                        /*                    $messageNew->assign_id  = 0;
                                            $messageNew->status  = 'UNREAD';
                                            $messageNew->related  = 0;
                                            $messageNew->required  = 0;
                                            $messageNew->read  = 0;*/
                        $messageNew->save();
                        //$this->info('Message #' . $messageNew->message_id . ' Received.');

                    }


                }

            }
        }

exit;
        
        $orderModel = new OrderModel;
        $start = microtime(true);
        $account = AccountModel::find(59);
        if ($account) {
            $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
            $endDate = date("Y-m-d H:i:s", time() - 300);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $orderList = $channel->listOrders($startDate, $endDate, $account->api_status, $account->sync_pages);
            foreach ($orderList as $order) {
                $order['channel_id'] = $account->channel->id;
                $order['channel_account_id'] = $account->id;
                $order['customer_service'] = $account->customer_service->id;
                $order['operator'] = $account->operator->id;
                //todo:订单状态获取
                $order['status'] = 'PAID';
                $oldOrder = $orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
                if (!$oldOrder) {
                    $orderModel->createOrder($order);
                }
            }
            $end = microtime(true);
            $lasting = round($end - $start, 3);
            echo $account->alias . ' 耗时' . $lasting . '秒';
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

            $productList = $channel->getOnlineProduct($start, 100);
            if ($productList) {
                foreach ($productList as $product) {

                    $is_add =true;
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
}