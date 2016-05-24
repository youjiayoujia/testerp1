@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="channel_id">渠道</th>
    <th class="sort" data-field="channel_account_id">渠道账号</th>
    <th class="sort" data-field="ordernum">订单号</th>
    <th class="sort" data-field="channel_ordernum">渠道订单号</th>
    <th>邮箱</th>
    <th>订单状态</th>
    <th>售后状态</th>
    <th class="sort" data-field="amount">总金额</th>
    <th>币种</th>
    <th>地址验证</th>
    <th class="sort" data-field="payment_date">支付时间</th>
    <th class="sort" data-field="affair_time">做账时间</th>
    <th class="sort" data-field="create_time">渠道创建时间</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->channel->name }}</td>
            <td>{{ $order->channelAccount->alias }}</td>
            <td>{{ $order->ordernum }}</td>
            <td>{{ $order->channel_ordernum }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->status_name }}</td>
            <td>{{ $order->active_name }}</td>
            <td>{{ $order->amount }}</td>
            <td>{{ $order->currency }}</td>
            <td>{{ $order->address_confirm_name }}</td>
            <td>{{ $order->payment_date }}</td>
            <td>{{ $order->affair_time == '0000-00-00' ? '' : $order->affair_time }}</td>
            <td>{{ $order->create_time }}</td>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->updated_at }}</td>
            <td>
                <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('order.edit', ['id'=>$order->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $order->id }}"
                   data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
