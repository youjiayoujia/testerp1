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
	
    //产品包装限制
    'multi_language' => [
        'de'=>'德语',
        'it'=>'意大利语',
        'fr'=>'法语',
        'zh'=>'中文',
    ],
	
	//产品投诉配置
	'productcomplaint'=>[
		'types'=>['质量不好','衣服有污迹','衣服破口','袖子长短不一'],
		'status'=>[0=>'正常',1=>'受投诉'],
	],
	'residualReport'=>[ 
		'status'=>[0=>'正常',1=>'产品残次'],
	],
	
	//供应商支付类型
	'product_supplier'=>[
		'pay_type'=>['ONLINE'=>'网上付款','BANK_PAY'=>'银行付款','CASH_PAY'=>'现金付款','OTHER_PAY'=>'其他方式'],
		'examine_status'=>['待审核','待复审','审核通过','审核不通过']
	],
];