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
    'pageSizes' => [10, 25, 50, 100, 300],
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

    'oversea' => [
        'status' => [
            'NEW' => '未处理',
            'FAIL' => '审核未通过',
            'PASS' => '审核通过',
            'PICKING' => '分拣中',
            'PACKING' => '包装中',
            'PACKED' => '包装完成',
            'SHIPPED' => '已发货',
        ],
        'print_status' => [
            'UNPRINT' => '未打印',
            'PRINTED' => '已打印'
        ]
    ],

    //库位excel地址
    'excelPath' => './uploads/excel/',
    'stockExcelPath' => './uploads/stockExcel/',
    'transfer_search' => [
        'id' => 'ID',
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
        'purchaseUser.name' =>'采购人',
        'examineStatus' => '审核状态',
        'write_off' =>'核销状态',
        'catalog.name' =>'分类',
        'examine_status'=>'产品审核状态',
        'stock.item.sku' => 'sku',
        'time.created_at'=>'创建时间',
        'order.ordernum' => '订单号',
        'productID'=>'Wish产品ID',
        'details.erp_sku'=>'erpSKU',
        'account_id' =>'账号',
        'sellerID' =>'销售人员',
        'price.number_sold' =>'产品销量',
        'time.publishedTime'=>'产品刊登时间',
        'thrend' => '趋势',
        'user.name' =>'姓名',
        'require_create' => '是否需要采购',
        'productSku.skuCode'=>'产品SKU',
        'product.token_id' => '帐号',
        'product.multiattribute' => '=属性=',
        'product.productStatusType' => '平台状态',
        'product.user_id' => '刊登人员',
        'products.status' => '商品状态',
        'products.c_name' => '商品中文名',
        'ipmSkuStock' => '可售库存',
        'is_erp' => 'sku是否匹配',
        'price.skuPrice' => '价格',
        'price.profitRate' => '利润率',
        'token_id' => '渠道帐号',
        'hang_num' => '挂号码',
        'hang_number' => '挂号码',
        'package_id' => '包裹id',
        'pay_type' =>'付款类型',
        'close_status'=>'付款状态',
        'by_id' => '买家ID',
        'profit_rate' => '利润率',
        'items.item.status' => 'SKU状态',
        'outer_type' => '出库/入库', 
        'inner_type' => '出入库类型',
        'warehouse_id' => '仓库',
        'logistics_id' => '物流方式',
        'time.printed_at' => '打印时间',
        'time.shipped_at' => '发货时间',
        'warehousePosition.name' => '库位',
        'item_id'=>'Ebay ItemID',
        'seller_id' =>'销售人员',
        'ebayProduct.site_name'=>'Ebay站点',
        'ebayProduct.paypal_email_address'=>'PayPal',
        'ebayProduct.currency'=>'币种',
        'ebayProduct.account_id'=>'账号',
        'ebayProduct.listing_type'=>'类型',
        'ebayProduct.multi_attribute'=>'多属性',
        'price.start_price'=>'价格',
        'time.start_time' => '上架时间',
        'sku'=>'EbaySku',
        'erp_sku' =>'ErpSku',
        'price.quantity_sold'=>'销量',
        'channel_name' => '渠道名称',
        'account.account' => '渠道帐号',
        'is_chinese' => '竞拍状态'
    ],
];

