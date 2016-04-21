<?php

namespace App\Modules;

abstract class BaseChannelModule
{
    /**
     * 获取订单 
     *
     * @param 待定
     *
     */
    abstract public function getOrder();

    /**
     * 订单列表 
     *
     * @param 待定
     *
     */
    abstract public function orderLists();
}