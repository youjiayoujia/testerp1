@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="name">渠道账号</th>
    <th class="sort" data-field="alias">渠道账号别名</th>
    <th class="sort" data-field="channel_id">渠道类型</th>
    <th>所在国家</th>
    <th>账号对应域名</th>
    <th>客服邮箱地址</th>
    <th>订单前缀</th>
    <th>订单同步周期</th>
    <th>默认运营人员</th>
    <th>默认客服人员</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $account)
        <tr>
            <td>{{ $account->id }}</td>
            <td>{{ $account->account }}</td>
            <td>{{ $account->alias }}</td>
            <td>{{ $account->channel->name }}</td>
            <td>{{ $account->country?$account->country->cn_name:'' }}</td>
            <td>{{ $account->domain }}</td>
            <td>{{ $account->service_email }}</td>
            <td>{{ $account->order_prefix }}</td>
            <td>{{ $account->sync_cycle }}</td>
            <td>{{ $account->operator->name }}</td>
            <td>{{ $account->customer_service->name }}</td>
            <td>{{ $account->created_at }}</td>
            <td>{{ $account->updated_at }}</td>
            <td>
                <a href="{{ route('channelAccount.show', ['id'=>$account->id]) }}" class="btn btn-info btn-xs" title="查看">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route('channelAccount.edit', ['id'=>$account->id]) }}" class="btn btn-warning btn-xs" title="编辑">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <button class="btn btn-primary btn-xs"
                        data-toggle="modal"
                        data-target="#myModal{{ $account->id }}"
                        title="API设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $account->id }}"
                   data-url="{{ route('channelAccount.destroy', ['id' => $account->id]) }}" title="删除">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
        @if($account->channel->name == 'Amazon')
            @include('channel.account.api.amazon')
        @endif
    @endforeach
@stop
