<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:51
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>渠道</th>
    <th>订单状态</th>
    <th>订单创建后N小时</th>
    <th>订单支付N小时</th>
    <th>承运商</th>
    <th>追踪号上传方式</th>
    <th>设置人员</th>
    <th>优先级</th>
    <th>是否启用</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $mark)
        <tr>
            <td>{{ $mark->id }}</td>
            <td>{{ $mark->channel_id }}</td>
            <td>{{ $mark->order_status }}</td>
            <td>{{ $mark->order_create }}</td>
            <td>{{ $mark->order_pay }}</td>
            <td>{{ $mark->assign_shipping_logistics }}</td>
            <td>{{ $mark->is_upload }}</td>
            <td>{{ $mark->user_id }}</td>
            <td>{{ $mark->priority }}</td>
            <td>{{ $mark->is_use }}</td>

            <td>
                <a href="{{ route('paypal.show', ['id'=>$mark->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('paypal.edit', ['id'=>$mark->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $mark->id }}"
                   data-url="{{ route('paypal.destroy', ['id' => $mark->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop