<?php
/**
 * Message配置文件
 * @author Norton
 * @datetime 2016-6-21 16:37:32
 */
return [
    'attachmentPath' => 'public/uploads/message/attachments/',
    'attachmentSrcPath' => 'uploads/message/attachments/',
    'statusText' => [
        'UNREAD' => '未读',
        'PROCESS' => '待处理',
        'COMPLETE' => '已处理'
    ],

    'aliexpress' => [
        'issueType' => [
            'WAIT_SELLER_CONFIRM_REFUND'    => '买家提起纠纷',
            'SELLER_REFUSE_REFUND'          => '卖家拒绝纠纷',
            'ACCEPTISSUE'                   => '卖家接受纠纷',
            'WAIT_BUYER_SEND_GOODS'         => '等待买家发货',
            'WAIT_SELLER_RECEIVE_GOODS'     => '买家发货，等待卖家收货',
            'ARBITRATING'                   => '仲裁中',
            'SELLER_RESPONSE_ISSUE_TIMEOUT' => '卖家响应纠纷超时',
        ]
    ],
];

