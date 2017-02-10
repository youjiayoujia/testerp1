<?php
/**
 * Created by PhpStorm.
 * User: norton
 * Date: 2017/1/3
 * Time: 上午9:38
 */
return [
    //ebay平台
    'ebay' => [
        'feedback' => [
            'Positive' => '好评',
            'Neutral' => '中评',
            'Negative' => '差评',
        ],
        'case' =>[
            'type' => [
                'EBP_INR' => 'EBP_INR',
                'EBP_SNAD' => 'EBP_SNAD',
                'RETURN' => 'RETURN',
            ],
            'status' => [
                'CLOSED' => 'CLOSED',
                'MY_RESPONSE_DUE' => 'MY_RESPONSE_DUE',
                'OPEN' => 'OPEN',
                'OTHER' => 'OTHER',
                'OTHER_PARTY_RESPONSE_DUE' => 'OTHER_PARTY_RESPONSE_DUE',
            ],
        ],
    ],

    //wish平台
    'wish' => [
        'refund' => [
            'reason_code' => [
                '-1'  => '其他',
                '18'  => '误下单了',
                '20'  => '配送时间过长',
                '22'  => '商品不合适',
                '23'  => '收到错误商品',
                '24'  => '商品为假冒伪劣品',
                '25'  => '商品已损坏',
                '26'  => '商品与描述不符',
                '27'  => '商品与清单不符',
                '30'  => '产品被配送至错误的地址',
                '31'  => '用户提供了错误的地址',
                '32'  => '商品退还至发货人',
                '33'  => 'Incomplete Order',
                '34'  => '店铺无法履行订单',
                '1001'  => 'Received the wrong color',
                '1002'  => 'Item is of poor quality',
                '1004'  => 'Product listing is missing information',
                '1005'  => 'Item did not meet expectations',
                '1006'  => 'Package was empty'
            ],
        ]
    ],

    //aliexpress平台
];