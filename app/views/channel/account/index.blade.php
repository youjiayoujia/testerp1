@extends('common.table')
@section('title') 渠道账号列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('channelAccount.index') }}">渠道账号</a></li>
        <li class="active">渠道账号列表</li>
    </ol>
@stop
@section('tableTitle') 渠道账号列表 @stop
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>渠道</th>
    <th>名称</th>
    <th>账户</th>
    <th>前缀</th>
    <th>简介</th>
    <th>创建者</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>更新者</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $account)
        <tr>
            <td>{{ $account->id }}</td>
            <td>{{ $account->channel->name }}</td>
            <td>{{ $account->title }}</td>
            <td>{{ $account->account }}</td>
            <td>{{ $account->prefix }}</td>
            <td>{{ $account->brief }}</td>
            <td>{{ $account->created_by }}</td>
            <td>{{ $account->created_at }}</td>
            <td>{{ $account->updated_by }}</td>
            <td>{{ $account->updated_at }}</td>
            <td>
                <a href="{{ route('channelAccount.show', ['id'=>$account->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('channelAccount.edit', ['id'=>$account->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $account->id }}"
                   data-url="{{ route('channelAccount.destroy', ['id' => $account->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
