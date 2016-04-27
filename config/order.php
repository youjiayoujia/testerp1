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
        'NEW' => '未处理',
        'PREPARED' => '准备发货',
        'NEED' => '需补货',
        'PACKED' => '打包完成',
        'SHIPPED' => '发货完成',
        'COMPLETE' => '订单完成',
        'CANCEL' => '取消订单',
        'ERROR' => '订单异常',
    ],

    //售后状态
    'active' => [
        'NORMAL' => '正常',
        'VERIFY' => '验证中',
        'CHARGEBACK' => '客户CB',
        'STOP' => '暂停发货',
        'RESUME' => '恢复正常'
    ],

    //订单地址
    'address' => [
        '0' => '未验证',
        '1' => '已验证'
    ],

    //币种
    'currency' => [
        'USD',
        'GBP',
        'EUR',
        'NOK',
        'CAD',
        'AUD',
        'CHF',
        'SEK',
        'PLN',
        'RUB',
        'MXN',
        'DKK',
        'SAR',
        'TWD',
        'JPY',
        'HKD'
    ],

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
        'NEW' => '未发货',
        'PACKED' => '准备发货',
        'SHIPPED' => '已发货'
    ],

    //是否有效
    'product_status' => [
        '0' => '无效',
        '1' => '有效'
    ],
];