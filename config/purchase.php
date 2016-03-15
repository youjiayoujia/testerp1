<?php
return [
    //采购条目配置
    'purchaseItem' =>[
		'status'=>['未处理','采购中','已对单','部分入库','已入库','取消'],
		'costExamineStatus'=>['未审核','不通过','审核通过'],
		'type'=>['订单','囤货'],
		'platforms' => ['choies', 'aliexpress', 'amazon', 'ebay', 'wish', 'Lazada'],
		'active' => ['正常','报缺', '报等', '残次', '图货不一'],
	],
    //采购单配置
    'purchaseOrder' =>[
		'status'=>['未处理','开始采购','已到货','取消'],
		'examineStatus'=>['未审核','不通过','审核通过'],
		'close_status'=>['未结算','已结算'],
	],
];