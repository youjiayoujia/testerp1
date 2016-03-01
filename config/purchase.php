<?php
return [
    //采购条目配置
    'purchaseItem' =>[
		'status'=>['未生成订单','已生成采购单','审核通过','采购中','采购完成'],
		'type'=>['订单','囤货'],
		'platforms' => ['choies', 'aliexpress', 'amazon', 'ebay', 'wish', 'Lazada'],
	],
    //采购单配置
    'purchaseOrder' =>[
		'status'=>['未审核','审核通过','采购中','已取消','已对单','采购完成'],
		'close_tatus'=>['未结算','已结算'],
		'active' => ['报缺', '报等', '残次', '图货不一'],
	],
];