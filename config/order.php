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
        'new' => '未处理',
        'confirmed' => '已确认',
        'need' => '需补货',
        'arrived' => '货物到齐',
        'shipped' => '发货完成',
        'completed' => '订单完成',
        'cancel' => '取消订单',
        'error' => '订单异常',
    ],

    //售后状态
    'active' => [
        'normal' => '正常',
        'accounting' => '验证中',
        'chargeback' => '客户CB',
        'suspend' => '暂停发货',
        'recovery' => '恢复正常'
    ],

    //订单地址
    'address' => [
        '0' => '未验证',
        '1' => '已验证'
    ],

    //币种
    'currency' => ['USD', 'GBP', 'EUR', 'NOK', 'CAD', 'AUD', 'CHF', 'SEK', 'PLN', 'RUB', 'MXN', 'DKK', 'SAR', 'TWD', 'JPY', 'HKD'],

    //种类
    'shipping' => [
        'express' => '快递',
        'packet' => '小包'
    ],

    //支付方式
    'payment' => ['GC', 'IDEAL', 'OC', 'PP', 'SOFORT'],

    //是否
    'whether' => [
        '0' => '否',
        '1' => '是'
    ],

    //发货状态
    'ship_status' => [
        'not_shipped' => '未发货',
        'ready_shipped' => '准备发货',
        'already_shipped' => '已发货'
    ],

    //是否有效
    'product_status' => [
        '0' => '无效',
        '1' => '有效'
    ],
];