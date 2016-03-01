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
    ],
	//产品异常配置
	'productAbnormal'=>[
		'type'=>['reportMissing'=>'报缺','reportWaiting'=>'报等','imageNotProduct'=>'图货不一'],
		'status'=>[
		'reportMissing'=>[0=>'正常',1=>'预报缺',2=>'核实报缺'],
		'reportWaiting'=>[0=>'正常',1=>'报等'], 
		'imageNotProduct'=>[0=>'正常',1=>'图货不一'],
		]
	], 
	//产品投诉配置
	'productcomplaint'=>[
		'types'=>['质量不好','衣服有污迹','衣服破口','袖子长短不一'],
		'status'=>[0=>'正常',1=>'受投诉'],
	],
	'residualReport'=>[ 
		'status'=>[0=>'正常',1=>'产品残次'],
	],
];