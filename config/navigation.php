<?php

/**
 * 导航配置文件
 *
 * name: 主导航名称
 * icon: 导航图标
 * url: 导航链接
 * subnavigations: 子菜单集合
 *      name: 子菜单名称
 *      url: 子菜单链接
 *      icon: 子菜单图标
 *
 * @author Vincent <nyewon@gmail.com>
 */
return [
    //产品导航
    [
        'name' => '产品',
        'icon' => 'tags',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '产品管理' => [
                [
                    'name' => '品类Category',
                    'url' => 'catalog.index',
                    'icon' => '',
                ],
                [
                    'name' => '产品Item',
                    'url' => 'item.index',
                    'icon' => '',
                ],
                [
                    'name' => '图片',
                    'url' => 'productImage.index',
                    'icon' => '',
                ],
            ],
            '选款' => [
                [
                    'name' => '选款需求',
                    'url' => 'productRequire.index',
                    'icon' => '',
                ],
                [
                    'name' => '选款Model',
                    'url' => 'product.index',
                    'icon' => '',
                ],
                [
                    'name' => '选款选中',
                    'url' => 'SelectProduct.index',
                    'icon' => '',
                ],
                [
                    'name' => '选款产品编辑',
                    'url' => 'EditProduct.index',
                    'icon' => '',
                ],
                [
                    'name' => '选款产品审核',
                    'url' => 'ExamineProduct.index',
                    'icon' => '',
                ],
            ],
			 '通关报关' => [
                [
                    'name' => 'home',
                    'url' => 'customsClearance.index',
                    'icon' => '',
                ],
                [
                    'name' => '三宝产品',
                    'url' => 'product.index',
                    'icon' => '',
                ],
                [
                    'name' => '三宝package',
                    'url' => 'SelectProduct.index',
                    'icon' => '',
                ],
                [
                    'name' => '中邮运单号列表',
                    'url' => 'EditProduct.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //订单导航
    [
        'name' => '订单',
        'icon' => 'list-alt',
        'url' => '',
        'type' => '',
        'subnavigations' => [
            [
                'name' => '订单',
                'url' => 'order.index',
                'icon' => '',
            ],
        ],
    ],
    //仓储导航
    [
        'name' => '仓储',
        'icon' => 'home',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '发货' => [
                [
                    'name' => '包裹',
                    'url' => 'package.index',
                    'icon' => '',
                ],
                [
                    'name' => '拣货',
                    'url' => 'pickList.index',
                    'icon' => '',
                ],
                [
                    'name' => '拣货单异常',
                    'url' => 'errorList.index',
                    'icon' => '',
                ],
            ],
            '仓储' => [
                [
                    'name' => '仓库',
                    'url' => 'warehouse.index',
                    'icon' => '',
                ],
                [
                    'name' => '库位',
                    'url' => 'warehousePosition.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存',
                    'url' => 'stock.index',
                    'icon' => '',
                ],
                [
                    'name' => '入库',
                    'url' => 'stockIn.index',
                    'icon' => '',
                ],
                [
                    'name' => '出库',
                    'url' => 'stockOut.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存调整',
                    'url' => 'stockAdjustment.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存调拨',
                    'url' => 'stockAllotment.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存盘点',
                    'url' => 'stockTaking.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存结转',
                    'url' => 'stockCarryOver.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //物流导航
    [
        'name' => '物流',
        'icon' => 'plane',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '物流管理' => [
                [
                    'name' => '物流商',
                    'url' => 'logisticsSupplier.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流方式',
                    'url' => 'logistics.index',
                    'icon' => '',
                ],
                [
                    'name' => '跟踪号',
                    'url' => 'logisticsCode.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流分区报价',
                    'url' => 'logisticsZone.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流分配规则',
                    'url' => 'logisticsRule.index',
                    'icon' => '',
                ],
            ]
        ],
    ],
    //采购导航
    [
        'name' => '采购',
        'icon' => 'shopping-cart',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '采购管理' => [
                [
                    'name' => '采购需求',
                    'url' => 'purchaseItem.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购条目',
                    'url' => 'purchaseItemList.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购单',
                    'url' => 'purchaseOrder.index',
                    'icon' => '',
                ],
				[
                    'name' => '打印采购单',
                    'url' => 'printPurchaseOrder.index',
                    'icon' => '',
                ],
				[
                    'name' => '采购单结算',
                    'url' => 'closePurchaseOrder.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购对单',
                    'url' => 'purchaseList.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购入库',
                    'url' => 'purchaseStockIn.index',
                    'icon' => '',
                ],
                [
                    'name' => '异常采购条目',
                    'url' => 'purchaseAbnormal.index',
                    'icon' => '',
                ],
                [
                    'name' => '异常采购单',
                    'url' => 'purchaseOrderAbnormal.index',
                    'icon' => '',
                ],
            ],
            '供应链' => [
                [
                    'name' => '供货商',
                    'url' => 'productSupplier.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //渠道导航
    [
        'name' => '渠道',
        'icon' => 'transfer',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '渠道管理' => [
                [
                    'name' => '渠道',
                    'url' => 'channel.index',
                    'icon' => '',
                ],
                [
                    'name' => '账号',
                    'url' => 'channelAccount.index',
                    'icon' => '',
                ],
            ]
        ],
    ],
    //财务导航
    [
        'name' => '财务',
        'icon' => 'piggy-bank',
        'url' => 'dashboard.index',
    ],
    //客户导航
    [
        'name' => '客户',
        'icon' => 'user',
        'url' => 'dashboard.index',
        'type' => 'group',
        'subnavigations' => [
            '用户' => [
                [
                    'name' => '用户列表',
                    'url' => 'user.index',
                    'icon' => '',
                ],
                [
                    'name' => '添加用户',
                    'url' => 'user.create',
                    'icon' => '',
                ],
            ]
        ],

    ],
    //系统导航
    [
        'name' => '系统',
        'icon' => 'cog',
        'url' => '',
        'type' => '',
        'subnavigations' => [
            [
                'name' => '汇率',
                'url' => 'currency.index',
                'icon' => '',
            ],
            [
                'name' => '物流限制',
                'url' => 'logisticsLimits.index',
                'icon' => '',
            ],
            [
                'name' => '包装限制',
                'url' => 'wrapLimits.index',
                'icon' => '',
            ],
        ],
    ],
];

