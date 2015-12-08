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
        'name' => '品牌',
        'location' => 'brand',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'brand.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'brand.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '供货商',
        'location' => 'provider',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'provider.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'provider.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '选款需求',
        'location' => 'productRequire',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'productRequire.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'productRequire.create',
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
        'location' => '',
        'icon' => 'plane',
        'url' => '',
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

