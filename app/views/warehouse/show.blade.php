@extends('layouts.default')
@section('title') 仓库详情 : {{ $warehouse->name }} {{ $warehouse->type }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('Warehouse.index') }}">仓库</a></li>
        <li class="active"><strong>仓库详情 : {{ $warehouse->name }} {{ $warehouse->type }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">仓库详情 : {{ $warehouse->name }} {{ $warehouse->type }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $warehouse->id }}</dd>
                <dt>名称</dt>
                <dd>{{ $warehouse->name }}</dd>
                <dt>省</dt>
                <dd>{{ $warehouse->province }}</dd>
                <dt>市</dt>
                <dd>{{ $warehouse->city }}</dd>
                <dt>类型</dt>
                <dd>{{ $warehouse->type }}</dd>
                <dt>容积</dt>
                <dd>{{ $warehouse->volumn }}</dd>
                <dt>是否启用</dt>
                <dd>{{ $warehouse->is_available == 'Y' ? '是' : '否' }}</dd>
                <dt>是否是默认仓</dt>
                <dd>{{ $warehouse->is_default == 'Y' ? '是' : '否' }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $warehouse->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $warehouse->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop