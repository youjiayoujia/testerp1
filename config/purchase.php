<?php
return [
    //采购条目配置
    'purchaseItem' =>[
		//采购状态
		'status'=>['未处理','采购中','待检入库',' 部分入库','已入库','取消','新品待入库'],
		//价格审核状态
		'costExamineStatus'=>['采购价格未审核','采购价格不通过','采购价格审核通过'],
		//异常类型
		'active' => ['正常','报缺', '报等', '残次', '图货不一'],	
		//异常类型对应状态
		'active_status'=>[
		'1'=>[0=>'正常',1=>'预报缺',2=>'核实报缺'],
		'2'=>[0=>'正常',1=>'报等'], 
		'3'=>[0=>'正常',1=>'残次'],
		'4'=>[0=>'正常',1=>'图货不一'],
		]
	],
    //采购单配置
    'purchaseOrder' =>[
		//采购单状态
		'status'=>['草稿','开始采购','待检入库','部分入库','已完成','取消'],
		//采购单审核
		'examineStatus'=>['未审核','审核通过','二次审核','审核不通过'],
		//是否打印
		'print_status'=>['未打印','已打印'],
		//付款方式
		'pay_type'=>['ONLINE'=>'网上付款','BANK_PAY'=>'银行付款','CASH_PAY'=>'现金支付','OTHER_PAY'=>'其他方式'],
		//采购类型
		'type'=>['0'=>'普通','1'=>'特采'],
		//物流方式
		'carriage_type'=>['物流包邮','快递包邮','物流到付','快递不包邮','自提'],
		//核销状态
		'write_off'=>['未核销','待核销','已核销'],
		//采购单结算状态
		'close_status'=>['未付款','已付款'],
	],

	'require' =>[
		'0'=>'无需采购',
		'1'=>'需采购'
	],

	'thrend' =>[
		'1'=>'上涨',
		'2'=>'下跌',
		'3'=>'无销量',
		'4'=>'持平',
	]

	
];