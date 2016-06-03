<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;


use Test;

use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;
use App\Modules\Channel\ChannelModule;
use App\Models\PackageModel;
use DNS1D;


class TestController extends Controller
{
    public function __construct(OrderModel $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function test1()
    {
        echo DNS1D::getBarcodeHTML("4445645656", "C39");
        echo DNS1D::getBarcodeHTML("4445645656", "C39+");
        echo DNS1D::getBarcodeHTML("4445645656", "C39E");
        echo DNS1D::getBarcodeHTML("4445645656", "C39E+");
        echo DNS1D::getBarcodeHTML("4445645656", "C93");
        echo DNS1D::getBarcodeHTML("4445645656", "S25");
        echo DNS1D::getBarcodeHTML("4445645656", "S25+");
        echo DNS1D::getBarcodeHTML("4445645656", "I25");
        echo DNS1D::getBarcodeHTML("4445645656", "I25+");
        echo DNS1D::getBarcodeHTML("4445645656", "C128");
        echo DNS1D::getBarcodeHTML("4445645656", "C128A");
        echo DNS1D::getBarcodeHTML("4445645656", "C128B");
        echo DNS1D::getBarcodeHTML("4445645656", "C128C");
        echo DNS1D::getBarcodeHTML("44455656", "EAN2");
        echo DNS1D::getBarcodeHTML("4445656", "EAN5");
        echo DNS1D::getBarcodeHTML("4445", "EAN8");
        echo DNS1D::getBarcodeHTML("4445", "EAN13");
        echo DNS1D::getBarcodeHTML("4445645656", "UPCA");
        echo DNS1D::getBarcodeHTML("4445645656", "UPCE");
        echo DNS1D::getBarcodeHTML("4445645656", "MSI");
        echo DNS1D::getBarcodeHTML("4445645656", "MSI+");
        echo DNS1D::getBarcodeHTML("4445645656", "POSTNET");
        echo DNS1D::getBarcodeHTML("4445645656", "PLANET");
        echo DNS1D::getBarcodeHTML("4445645656", "RMS4CC");
        echo DNS1D::getBarcodeHTML("4445645656", "KIX");
        echo DNS1D::getBarcodeHTML("4445645656", "IMB");
        echo DNS1D::getBarcodeHTML("4445645656", "CODABAR");
        echo DNS1D::getBarcodeHTML("4445645656", "CODE11");
        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA");
        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T");
    }

    public function index()
    {
        $accountID = request()->get('id');
        $begin = microtime(true);
        $account = AccountModel::findOrFail($accountID);
        $startDate = date("Y-m-d H:i:s", strtotime('-30 day'));
        $endDate = date("Y-m-d H:i:s", strtotime('-12 hours'));
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $orderList = $channel->listOrders($startDate, $endDate, $status, 20);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            $order['channel_id'] = $account->channel->id;
            $order['channel_account_id'] = $account->id;
            if ($thisOrder) {
                $thisOrder->updateOrder($order);
            } else {
                $this->orderModel->createOrder($order);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
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

    public function lazadaOrdersList(){
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

    public function test(){
        $datas = DB::table('test')->get();

        foreach ($datas as $data)
        {
            $spu_id = DB::table('spus')->insertGetId(
                 array('spu' => $data->sku, 'status' =>  0,'created_at'=>'2015-10-16 16:33:00','updated_at'=>'2015-10-16 16:33:00')
            );
            
            $product_id = DB::table('products')->insertGetId(
                 array('spu_id' => $spu_id, 
                    'name' =>  $data->title,
                    'c_name'=>$data->c_name,
                    'model'=>$data->sku,
                    'weight'=>$data->weight,
                    'catalog_id'=>1,
                    'supplier_id'=>1,
                    'warehouse_id'=>1,
                    'default_image'=>0,
                    'status'=>1)
            );

            $sku_id = DB::table('items')->insertGetId(
                 array('product_id' => $product_id, 
                    'name' =>  $data->title,
                    'c_name'=>$data->c_name,
                    'sku'=>$data->sku,
                    'weight'=>$data->weight,
                    'purchase_price'=>$data->value,
                    'warehouse_position'=>$data->location,
                    'warehouse_id'=>1,
                    'catalog_id'=>1,
                    'supplier_id'=>1,
                    'status'=>1)
            );


        }
    }
}