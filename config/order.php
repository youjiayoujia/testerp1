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
    'status' => [
        '1' => 'new',
        '2' => '准备发货',
        '3' => '打包完成',
        '4' => '发货完成',
        '5' => '订单完成'
    ],

    //售后状态
    'active' => [
        '1' => '正常',
        '2' => '暂停发货',
        '3' => '恢复正常'
    ],

    //订单地址
    'address' => [
        '0' => '未验证',
        '1' => '已验证'
    ],

    //币种
    'currency' => ['ALL', 'USD', 'GBP', 'EUR'],

    //种类
    'shipping' => [
        'express' => '快递',
        'packet' => '小包'
    ],

    //支付方式
    'payment' => ['GC', 'PP', 'OC'],

    //是否
    'whether' => [
        '0' => '否',
        '1' => '是'
    ],

    //发货状态
    'ship_status' => [
        '1' => '未发货',
        '2' => '准备发货',
        '3' => '已发货'
    ],

    //是否有效
    'product_status' => [
        '0' => '删除',
        '1' => '有效'
    ],
];