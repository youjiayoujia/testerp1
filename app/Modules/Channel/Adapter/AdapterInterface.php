<?php
namespace App\Modules\Channel\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 下午2:51
 */
interface AdapterInterface
{
    //获取订单
    public function getOrder($orderID);

    //获取订单列表
    public function listOrders();

    //回传物流号
    public function returnTrack();

}