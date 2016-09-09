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
    
    //语言
    'multi_language' => [
        'en'=>'英语',
        'de'=>'德语',
        'it'=>'意大利语',
        'fr'=>'法语',
        'zh'=>'中文',
    ],

    //语言
    'examineStatus' => [
        'pending' => '未审核',
        'pass' => '已审核',
        'notpass' => '审核通过',
        'revocation' => '撤销审核'
    ],
	
	//产品投诉配置
	'productcomplaint'=>[
		'types'=>['质量不好','衣服有污迹','衣服破口','袖子长短不一'],
		'status'=>[0=>'正常',1=>'受投诉'],
	],
	'residualReport'=>[ 
		'status'=>[0=>'正常',1=>'产品残次'],
	],

    //产品留言板分类
    'question'=>[
        'types'=>['WISH业务','仓位管理','速卖通业务','采购','美工','质检售后支持','亚马逊业务','产品编辑','价格问题'],
    ],
	
	//供应商支付类型
	'product_supplier'=>[
		'pay_type'=>['ONLINE'=>'网上付款','BANK_PAY'=>'银行付款','CASH_PAY'=>'现金付款','OTHER_PAY'=>'其他方式'],
		'examine_status'=>['待审核','待复审','审核通过','审核不通过'],
        'type'=>['线下','线上','做货'],
		'file_path' => 'uploads/product/supplier/', //审核文件路径
	],

	'supplier_examine_status' =>[
		'0' => '待审核',
		'1' => '待复审',
		'2' => '审核通过',
		'3' => '审核不通过',
	],
];