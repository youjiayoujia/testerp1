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
        'channel.name' => '渠道(平台)',
        'channelAccount.account' => '渠道帐号',
        'logistics.code' => '物流简码',
        'logistics.name' => '物流方式',
        'tracking_no' => '物流追踪号',
        'status' => '状态',
        'order.status' => '订单状态',
        'order.active' => '售后状态',
        'active' => '售后状态',
        'country.code' => '国家简称',
        'time.created_at' => '时间',
        'price.amount' => '金额',
        'item.sku' => 'SKU',
        'items.sku' => 'SKU',
        'ordernum' => '订单号',
        'channel_ordernum' => '渠道订单号',
        'channelAccount.alias' => '渠道账号',
        'userService.name' => '客服人员',
        'email' => '邮箱',
        'currency' => '币种',
        'name' => '姓名',
        'zipcode' => '邮编',
        'type' => '类型',
        'purchaseOrder.status' => '采购单状态状态',
        'purchaseOrder.examineStatus' => '采购单审核状态状态',
        'supplier.name' => '供应商',
        'purchaseUser.name' =>'操作人员'
    ],
];

