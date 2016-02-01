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
        'subnavigations' => [
            [
                'name' => '品类',
                'url' => 'catalog.index',
                'icon' => '',
            ],
            [
                'name' => '供货商',
                'url' => 'productSupplier.index',
                'icon' => '',
            ],
            [
                'name' => '选款需求',
                'url' => 'productRequire.index',
                'icon' => '',
            ],
            [
                'name' => '产品图片',
                'url' => 'productImage.index',
                'icon' => '',
            ],
        ],
    ],
    //订单导航
    [
        'name' => '订单',
        'icon' => 'list-alt',
        'url' => 'dashboard.index',
    ],
    //仓储导航
    [
        'name' => '仓储',
        'icon' => 'home',
        'url' => '',
        'subnavigations' => [
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
        ],
    ],

    //物流导航
    [
        'name' => '物流',
        'icon' => 'plane',
        'url' => 'dashboard.index',
        'subnavigations' => [
            [
                'name' => '物流商',
                'url' => 'logisticsSupplier.index',
                'icon' => '',
            ],
            [
                'name' => '物流商物流方式',
                'url' => 'supplierShipping.index',
                'icon' => '',
            ],
            [
                'name' => '我们的物流方式',
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
        ],
    ],

    //采购导航
    [
        'name' => '采购',
        'icon' => 'shopping-cart',
        'url' => 'dashboard.index',
    ],
    //渠道导航
    [
        'name' => '渠道',
        'icon' => 'transfer',
        'url' => '',
        'subnavigations' => [
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
    ],
];

