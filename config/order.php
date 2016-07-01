<?php
/**
 * 订单配置文件
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/3/3
 * Time: 下午4:01
 */
return [
    //订单状态
    'status' => [
        'UNPAID' => '未付款',
        'PAID' => '已付款',
        'PREPARED' => '准备发货',
        'NEED' => '缺货',
        'PACKED' => '打包完成',
        'SHIPPED' => '发货完成',
        'COMPLETE' => '订单完成',
        'CANCEL' => '取消订单',
        'REVIEW' => '需审核',
    ],

    //售后状态
    'active' => [
        'NORMAL' => '正常',
        'VERIFY' => '验证中',
        'CHARGEBACK' => '客户CB',
        'STOP' => '暂停发货',
        'RESUME' => '恢复正常'
    ],

    //订单地址
    'address' => [
        '0' => '未验证',
        '1' => '已验证'
    ],

    //撤单原因
    'withdraw' => [
        '1' => '修改订单',
        '2' => '欠货重打印(7天以内禁止这样操作)',
        '3' => '缺货(通知客服决定是否联系)',
        '4' => '客户取消',
        '5' => '等确认情况(尽量别撤)',
        '6' => '物品被删',
        '7' => '亏本',
        '8' => 'PP或速卖通审查(要通知客户)',
        '9' => '账户冻结',
        '10' => '无法运输'
    ],

    //退款原因
    'reason' => [
        '1' =>'[没发货] 客户取消',
        '2' => '[没发货] 缺货中国仓',
        '3' => '[没发货] 亏本+物品被删',
        '4' => '[没发货] 付款审查/资金冻结',
        '5' => '[中国发] 物流问题',
        '6' => '[中国发] 没出国退回',
        '7' => '[中国发] 关税',
        '8' => '[海外仓] 缺货海外仓',
        '9' => '[海外仓] 物流问题',
        '10' => '质量问题(尺码色差不能用不满意)',
        '11' => '运输损坏',
        '12' => '发错货(中国仓)',
        '13' => '发错货(海外仓)',
        '14' => '广告错/SKU错/客户错',
        '15' => '漏配件'
    ],

    //退款类型
    'type' => [
        'FULL' => '全部退款',
        'PARTIAL' => '部分退款'
    ],

    //退款方式
    'refund' => [
        '1' => 'Paypal',
        '2' => '销售平台'
    ],

    //黑名单类型
    'blacklist_type' => [
        'CONFIRMED' => '确认黑名单',
        'SUSPECTED' => '疑似黑名单',
        'WHITE' => '白名单'
    ],

    //支付方式
    'payment' => ['GC', 'IDEAL', 'OC', 'PP', 'SOFORT'],

    //是否
    'whether' => [
        '0' => '否',
        '1' => '是'
    ],

    //发货状态
    'item_status' => [
        'NEW' => '未发货',
        'PACKED' => '准备发货',
        'SHIPPED' => '已发货'
    ],

    //是否有效
    'is_active' => [
        '0' => '无效',
        '1' => '有效'
    ],

    //订单黑名单excel地址
    'excelPath' => './uploads/excel/',
];