<?php
/**
 * 订单配置文件
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/3/3
 * Time: 下午4:01
 */
return [
    //订单状态
    'status' => ['new', '准备发货', '打包完成', '发货完成', '订单完成'],
    //售后状态
    'active' => ['正常', '暂停发货', '恢复正常'],
    //订单地址
    'address' => ['已验证', '未验证'],
    //币种
    'currency' => ['ALL', 'USD', 'GBP', 'EUR'],
    //种类
    'shipping' => ['快递', '小包'],
    //支付方式
    'payment' => ['GC', 'PP', 'OC'],
];