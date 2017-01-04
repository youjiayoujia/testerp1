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
    'pageSizes' => [10, 50, 100, 200, 500],
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
        'channel_ordernum' => '平台订单号',
        'channelAccount.alias' => '渠道账号',
        'userOperator.name' => '运营人员',
        'email' => '邮箱',
        'currency' => '币种',
        'name' => '名称',
        'zipcode' => '邮编',
        'type' => '类型',
        'purchaseOrder.status' => '采购单状态状态',
        'purchaseOrder.examineStatus' => '采购单审核状态状态',
        'supplier.name' => '供应商',
        'purchaseUser.name' => '采购人',
        'examineStatus' => '审核状态',
        'write_off' => '核销状态',
        'catalog.name' => '分类',
        'examine_status' => '产品审核状态',
        'stock.item.sku' => 'sku',
        'time.created_at' => '创建时间',
        'time.get_time' => '创建时间',
        'order.id' => '内单号',
        'productID' => 'Wish产品ID',
        'details.erp_sku' => 'erpSKU',
        'details.wish_sku' => 'WISH-SKU',
        'account_id' => '账号',
        'sellerID' => '销售人员',
        'price.number_sold' => '产品销量',
        'time.publishedTime' => '产品刊登时间',
        'thrend' => '趋势',
        'user.name' => '姓名',
        'require_create' => '是否需要采购',
        'productStatusType' => '广告状态',
        'groupId' => '产品分组',
        'productSku.skuCode' => '产品SKU',
        'product.token_id' => '帐号',
        'product.multiattribute' => '=属性=',
        'product.productStatusType' => '平台状态',
        'product.user_id' => '刊登人员',
        'products.status' => '商品状态',
        'products.c_name' => '商品中文名',
        'ipmSkuStock' => '可售库存',
        'is_erp' => 'sku是否匹配',
        'price.skuPrice' => '价格',
        'price.profit' => '利润',
        'token_id' => '渠道帐号',
        'hang_num' => '挂号码',
        'hang_number' => '挂号码',
        'package_id' => '包裹id',
        'pay_type' => '付款类型',
        'close_status' => '付款状态',
        'by_id' => '买家ID',
        'shipping_firstname' => '收货人',
        'items.item_status' => 'SKU状态',
        'outer_type' => '出库/入库',
        'inner_type' => '出入库类型',
        'warehouse_id' => '仓库',
        'logistics_id' => '物流方式',
        'time.printed_at' => '打印时间',
        'time.shipped_at' => '发货时间',
        'warehousePosition.name' => '库位',
        'item_id' => 'Ebay ItemID',
        'seller_id' => '销售人员',
        'ebayProduct.site_name' => 'Ebay站点',
        'ebayProduct.paypal_email_address' => 'PayPal',
        'ebayProduct.currency' => '币种',
        'ebayProduct.account_id' => '账号',
        'ebayProduct.listing_type' => '类型',
        'ebayProduct.multi_attribute' => '多属性',
        'erpProduct.status' => 'erp状态',
        'price.start_price' => '价格',
        'time.start_time' => '上架时间',
        'warehouse' => '仓库',
        'site' => '站点',
        'category' => 'Erp分类',
        'sku' => 'EbaySku',
        'erp_sku' => 'ErpSku',
        'price.quantity_sold' => '销量',
        'site_name' => '站点',
        'paypal_email_address' => 'PayPal',
        'listing_type' => '上架类型',
        'multi_attribute' => '多属性',
        'details.seller_id' => '销售人员',
        'channel_name' => '渠道名称',
        'account.account' => '渠道帐号',
        'is_chinese' => '竞拍状态',
        'packages.is_mark' => '是否标记',
        'remarks.type' => '审核类型',
        'packages.logistics.code' => '物流方式简码',
        'packages.tracking_no' => '追踪号',
        'price.profit_rate' => '利润率',
        'packages.status' => '包裹状态',

        'product.status' => '==ERP状态==',
        'account' => '=帐号=',

        'parent.shipmentCostNum' => '批次号',
        'questionUser.name' => '提问人',
        'answerUser.name' => '解答人',
        'question_group' => '指定分组',
        'skuName.c_name' => '产品名',
        'messageSku.sku' => 'sku',
        'joom_publish_product_detail.productID' => 'Joom产品ID',
        'joom_publish_product_detail.sku' => 'Joom原始sku',
        'enabled' => 'Joom平台状态',
        'catalog.id' => '品类',
        'spu_id' => 'SPU',
        'logistics.catalog.name' => '物流分类',
        'order.channel_ordernum' => '平台订单号',
        'purchaseItem.productItem.sku' => 'sku',
        'new_status' => '是否为新品',
        'html_mod'=>'英文描述',
        'items.item.sku' => 'sku',
        'check_status' => '审核状态',
        'shipping_country' => '收货国家',
        'assign_id' => '客服名称',
        'messages.status' => '消息状态',
        'refund' => '退款方式',
        'process_status'=> '处理状态',
        'customer_id' => '客服',
        'product_suppliers.examine_status' => '审核状态',
        'assign_id' => '客服',
        'from_name' => '用户昵称',
        'from' => '用户ID',
        'messages.labels' => '标签',
        'ebay_feedback.transaction_id' => 'transactionID',
        'ebay_feedback.ebay_item_id' => 'itemID',
        'ebay_feedback.commenting_user' => '买家ID',
        'ebay_feedback.comment_type' => '类型',
        'ebay_feedback.channel_account_id' => 'ebay账号',
        'spuMultiOption.en_description' => '英语',
        'spuMultiOption.de_description' => '德语',
        'spuMultiOption.fr_description' => '法语',
        'spuMultiOption.it_description' => '意大利语',
        'spuMultiOption.zh_description' => '中文',
        'catalog.catalogCategory.cn_name' => '一级分类',
        'is_second' => '状态',
        'catalog.second' => '二级分类',
        'mail_push.name' => '变量名称',
        'mail_push.code' => '变量代码',
        'message_statistics.compute_time' => '统计时间',
        'message_statistics.user_id' => '客服名称',

    ],
];
