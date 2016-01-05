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
    'pageSizes' => [10, 25, 50, 100],
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
];

