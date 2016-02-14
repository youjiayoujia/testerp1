<?php
return [
    //产品需求图片目录
    'requireimage' => 'uploads/require',
    //产品图片配置
    'image' => [
        'extensions' => ['jpg', 'gif', 'bmp'],
        'types' => ['public', 'original', 'choies', 'aliexpress', 'amazon', 'ebay', 'wish', 'Lazada'],
        'uploadPath' => 'uploads/product'
    ],
    //产品物流限制
    'package_limit'=> [
    	'1'=>'test1',
    	'2'=>'test2',
    	'3'=>'test3',
    ],
    //产品包装限制
    'carriage_limit'=> [
    	'1'=>'test1',
    	'2'=>'test2',
    	'3'=>'test3',
    ]
];