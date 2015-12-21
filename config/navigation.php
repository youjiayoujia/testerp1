<?php

/**
 * 导航配置文件
 *
 * name: 主导航名称
 * location: 当前导航对应位置
 * icon: 导航图标
 * url: 导航链接
 * subnavigations: 子菜单集合
 *      name: 子菜单名称
 *      location: 当前子菜单对应位置
 *      url: 子菜单链接
 *      icon: 子菜单图标
 *
 * @author Vincent <nyewon@gmail.com>
 */
return [
    [
        'name' => '常用',
        'location' => 'dashboard',
        'icon' => 'dashboard',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '工作台',
                'location' => '',
                'url' => 'product.index',
                'icon' => '',
            ],
            [
                'name' => '常用统计',
                'location' => '',
                'url' => 'product.index',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '产品',
        'location' => 'product',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'product.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'product.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '品类',
        'location' => 'catalog',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'catalog.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'catalog.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '仓库',
        'location' => 'warehouse',
        'icon' => 'home',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'warehouse.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'warehouse.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '订单',
        'location' => '',
        'icon' => 'list-alt',
        'url' => '',
    ],
    [
        'name' => '仓储',
        'location' => '',
        'icon' => 'home',
        'url' => '',
    ],
    [
        'name' => '物流',
        'location' => 'logistics',
        'icon' => 'plane',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '物流商列表',
                'location' => null,
                'url' => 'logistics.index',
                'icon' => '',
            ],
            [
                'name' => '物流商新增',
                'location' => 'create',
                'url' => 'logistics.create',
                'icon' => '',
            ],
            [
                'name' => '物流商物流方式列表',
                'location' => null,
                'url' => 'logisticsType.index',
                'icon' => '',
            ],
            [
                'name' => '物流商物流方式新增',
                'location' => 'create',
                'url' => 'logisticsType.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '采购',
        'location' => '',
        'icon' => 'shopping-cart',
        'url' => '',
    ],
    [
        'name' => '渠道',
        'location' => '',
        'icon' => 'transfer',
        'url' => '',
    ],
    [
        'name' => '财务',
        'location' => '',
        'icon' => 'piggy-bank',
        'url' => '',
    ],
    [
        'name' => '客户',
        'location' => '',
        'icon' => 'user',
        'url' => '',
    ],
];

