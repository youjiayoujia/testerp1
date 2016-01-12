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
        'url' => '',
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
        ],
    ],
    //物流导航
    [
        'name' => '物流',
        'icon' => 'plane',
        'url' => '',
    ],
    //采购导航
    [
        'name' => '采购',
        'icon' => 'shopping-cart',
        'url' => '',
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
        'url' => '',
    ],
    //客户导航
    [
        'name' => '客户',
        'icon' => 'user',
        'url' => '',
    ],
];

