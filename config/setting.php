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

    'transfer_search' => [
        'warehouse.name' => '仓库',
        'channel.name' => '渠道',
        'channelAccount.account' => '渠道帐号',
        'logistics.short_code' => '物流简码',
        'logistics.logistics_type' => '物流方式',
        'tracking_no' => '物流追踪号',
        'status' => '状态',
        'order.status' => '订单状态',
        'order.active' => '订单售后状态',
        'active' => '订单售后状态',
        'country.code' => '国家简称',
        'item.sku' => 'sku'
    ],
];

