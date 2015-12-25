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
    [
        'name' => '产品',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
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
        ],
    ],
    [
        'name' => '订单',
        'icon' => 'list-alt',
        'url' => '',
    ],
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
                'name' => '入库',
                'url' => 'stockIn.index',
                'icon' => '',
            ],
            [
                'name' => '出库',
                'url' => 'stockOut.index',
                'icon' => '',
            ],
        ],
    ],
    
    [
        'name' => '库存调整',
        'icon' => 'home',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'url' => 'adjustment.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'url' => 'adjustment.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '物流',
        'icon' => 'plane',
        'url' => '',
    ],
    [
        'name' => '采购',
        'icon' => 'shopping-cart',
        'url' => '',
    ],
    [
        'name' => '渠道',
        'icon' => 'transfer',
        'url' => '',
    ],
    [
        'name' => '财务',
        'icon' => 'piggy-bank',
        'url' => '',
    ],
    [
        'name' => '客户',
        'icon' => 'user',
        'url' => '',
    ],
];

