@extends('layouts.default')
@section('title') 供应商详情 : {{ $provider->name }} {{ $provider->url }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('provider.index') }}">供应商</a></li>
        <li class="active"><strong>供应商详情 : {{ $provider->name }} {{ $provider->url }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">供应商详情 : {{ $provider->name }} {{ $provider->url }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $provider->id }}</dd>
                <dt>名称</dt>
                <dd>{{ $provider->name }}</dd>
                <dt>详细地址</dt>
                <dd>{{ $provider->detail_address }}</dd>
                <dt>地址</dt>
                <dd>{{ $provider->address }}</dd>
                <dt>是否是线上供货商</dt>
                <dd>{{ $provider->type }}</dd>
                <dt>上线供货商网址</dt>
                <dd>{{ $provider->url }}</dd>
                <dt>电话</dt>
                <dd>{{ $provider->telephone }}</dd>
                <dt>采购员</dt>
                <dd>{{ $provider->purchase_id }}</dd>
                <dt>评级</dt>
                <dd>{{ $provider->level }}</dd>
                <dt>创建人</dt>
                <dd>{{ $provider->created_by }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $provider->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $provider->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop