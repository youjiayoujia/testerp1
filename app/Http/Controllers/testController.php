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
            'AWS_SECRET_ACCESS_KEY' => 'gH13dKCgTtBloKPhHFIBmDTv/sbNoUNJkWIMK/Je',
            'AWSAccessKeyId' => 'AKIAJE7QKBLWVEGMZRJQ',
            'MarketplaceId.Id.1' => 'ATVPDKIKX0DER',
            'SellerId' => 'A3THBIK7QYKUUV',
        ];

        $orderDetail = Channel::driver('Amazon', $config)->getOrder('112-8698241-2648200');
        echo "<pre>";
        var_dump($orderDetail);
    }
}