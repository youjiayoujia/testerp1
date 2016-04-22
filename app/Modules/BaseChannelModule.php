<?php
/**
 * 渠道基Module ，定义通用的方法 
 *
 * @author mc<178069409@qq.com>
 * Date: 2016/04/22 14:53
 *
 */
namespace App\Modules;

abstract class BaseChannelModule
{
    /**
     * 获取订单 
     *
     * @param none
     *
     */
    abstract public function getOrder();

    /**
     * 订单列表 
     *
     * @param none
     *
     */
    abstract public function listOrders();

    /**
     * 订单详情 
     *
     * @param none
     *
     */
    abstract public function listOrderItems();
}