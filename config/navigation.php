<?php

/**
 * 导航配置配置文件
 *
 * name: 主导航名称
 * active: 载入是否激活该导航
 * icon: 导航图片
 * subnavigations: 子菜单集合
 *      name: 子菜单名称
 *      url: 子菜单链接
 *      icon: 子菜单图标
 *      tabid: 子菜单标签ID, 保持唯一
 *
 * @author Vincent <nyewon@gmail.com>
 */
return [
    [
        'name' => '控制面板',
        'active' => 'active',
        'icon' => 'fa fa-anchor',
        'subnavigations' => [
            [
                'name' => '主页',
                'url' => 'dashboard.index',
                'icon' => 'home',
                'tabid' => 'home',
            ],
        ],
    ],
    [
        'name' => '产品管理',
        'active' => '',
        'icon' => 'fa fa-check-square-o',
        'subnavigations' => [
            [
                'name' => '产品列表',
                'url' => 'dashboard.test',
                'icon' => 'home',
                'tabid' => 'product',
            ],
        ],
    ],
];

