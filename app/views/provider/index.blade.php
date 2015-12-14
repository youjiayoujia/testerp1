@extends('common.table')
@section('title') 供货商列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('provider.index') }}">供货商</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 供货商列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>名称</th>
    <th>具体地址</th>
    <th>经纬度</th>
    <th>是否是线上供货商</th>
    <th>线上供货商网址</th>
    <th>电话</th>
    <th>采购员</th>
    <th>评级</th>
    <th>创建人</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $provider)
        <tr>
            <td>{{ $provider->id }}</td>
            <td>{{ $provider->name }}</td>
            <td>{{ $provider->detail_address}}</td>
            <td>{{ $provider->address }}</td>
            <td>{{ $provider->type }} </td>
            <td>{{ $provider->url }}</td>
            <td>{{ $provider->telephone }}</td>
            <td>{{ $provider->purchase_id }}</td>
            <td>{{ $provider->level }}</td>
            <td>{{ $provider->created_by}}</td>
            <td>{{ $provider->created_at }}</td>
            <td>
                <a href="{{ route('provider.show', ['id'=>$provider->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('provider.edit', ['id'=>$provider->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $provider->id }}"
                   data-url="{{ route('provider.destroy', ['id' => $provider->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
