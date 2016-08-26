<?php

/**
 * 系统常用配置文件
 *
 * @author Vincent <nyewon@gmail.com>
 */
return [
    /**
     * 默认分页条数
     */
    'pageSize' => 10,
    /**
     * 可选分页条数
     */
    'pageSizes' => [10, 25, 50, 100],
    /**
     * 默认排序字段
     */
    'orderField' => 'id',
    /**
     * 默认排列顺序
     */
    'orderDirection' => 'desc',
    /**
     * 默认标题
     */
    'titles' => [
        'index' => '列表',
        'show' => '详情',
        'create' => '新增',
        'edit' => '编辑',
    ],

    //库位excel地址
    'excelPath' => './uploads/excel/',

    'stockExcelPath' => './uploads/stockExcel/',

    'modules' =>[
        'amazon' => [
            'AWSAccessKeyId' => 'AKIAJV5BH3ZQG46ZRALQ',
            'SignatureVersion' => '2',
            'MarketplaceId.Id.1' => 'ATVPDKIKX0DER',
            'SellerId' => 'A2UWZA8JOQYEBT',
            'SignatureMethod' => 'HmacSHA256',
            'Timestamp' => gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()),
            'Version' => '2013-09-01',
        ],
    ],

    'AWS_SECRET_ACCESS_KEY' => '0CLC6sf20PN27a+vvEiv8z5gMc2yWj3pTCYYu6Dz',
];

