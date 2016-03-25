<?php
return [
    //采购条目配置
    'purchaseItem' =>[
		'status'=>['未处理','采购中','已对单','取消'],
		'costExamineStatus'=>['未审核','不通过','审核通过'],
		'type'=>['订单','囤货'],
		'platforms' => ['choies', 'aliexpress', 'amazon', 'ebay', 'wish', 'Lazada'],
		'active' => ['正常','报缺', '报等', '残次', '图货不一'],
	],
    //采购单配置
    'purchaseOrder' =>[
		'status'=>['未处理','开始采购','已对单','部分入库','已入库','取消'],
		'examineStatus'=>['未审核','不通过','审核通过'],
		'close_status'=>['未结算','已结算'],
	],
	//产品异常配置
	'productAbnormal'=>[
		'status'=>[
		'1'=>[0=>'正常',1=>'预报缺',2=>'核实报缺'],
		'2'=>[0=>'正常',1=>'报等'], 
		'3'=>[0=>'正常',1=>'残次'],
		'4'=>[0=>'正常',1=>'图货不一'],
		]
		], 
];