<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;

use Channel;

class TestController extends Controller
{
    public function index()
    {
        $config = [
            'serviceUrl' => 'https://mws.amazonservices.com',
            'AWS_SECRET_ACCESS_KEY' => 'GSIczWEj5HXOwX0itVw62MU/sABECvu24XaFYFhH',
            'AWSAccessKeyId' => 'AKIAIVJDQNXZFUIZWIXQ',
            'MarketplaceId.Id.1' => 'ATVPDKIKX0DER',
            'SellerId' => 'ARAZZBXXIK68F',
        ];


        $startDate = '2016-05-01 00:00:00';
        $endDate = date('Y-m-d 00:00:00', time());
        $status = ['Unshipped', 'PartiallyShipped'];
        $orderList = Channel::driver('Amazon', $config)->listOrders($startDate, $endDate, $status);
        echo "<pre>";
        var_dump($orderList);
        echo "<hr>";
        $orderID = '112-8698241-2648200';
        $orderDetail = Channel::driver('Amazon', $config)->getOrder($orderID);
        echo "<pre>";
        var_dump($orderDetail);


    }
}