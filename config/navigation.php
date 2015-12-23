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
        'name' => '供货商',
        'location' => 'Supplier',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'supplier.index',
                'icon' => '',
            ],
            [
                'name' => '新增', 
                'location' => 'create',
                'url' => 'supplier.create',
                'icon' => '',
            ],
        ],
    ],
    [
        'name' => '选款需求',
        'location' => 'Require',
        'icon' => 'tags',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '列表',
                'location' => null,
                'url' => 'require.index',
                'icon' => '',
            ],
            [
                'name' => '新增',
                'location' => 'create',
                'url' => 'require.create',
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
        'location' => 'warehouse',
        'icon' => 'home',
        'url' => '',
        'subnavigations' => [
            [
                'name' => '仓库',
                'location' => null,
                'url' => 'warehouse.index',
                'icon' => '',
            ],
            [
                'name' => '库位',
                'location' => 'null',
                'url' => 'position.index',
                'icon' => '',
            ],
        ],
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

