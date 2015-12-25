@extends('layouts.default')
@section('title') 物流方式shippings详情 : {{ $logistics->short_code }} {{ $logistics->logistics_type }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流方式shippings</a></li>
        <li class="active"><strong>物流方式shippings详情 : {{ $logistics->short_code }} {{ $logistics->logistics_type }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">物流方式shippings详情 : {{ $logistics->short_code }} {{ $logistics->logistics_type }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $logistics->id }}</dd>
                <dt>物流方式简码</dt>
                <dd>{{ $logistics->short_code }}</dd>
                <dt>物流方式名称</dt>
                <dd>{{ $logistics->logistics_type }}</dd>
                <dt>种类</dt>
                <dd>{{ $logistics->species }}</dd>
                <dt>仓库</dt>
                <dd>{{ $logistics->warehouse->name }}</dd>
                <dt>物流商</dt>
                <dd>{{ $logistics->supplier->name }}</dd>
                <dt>物流商物流方式</dt>
                <dd>{{ $logistics->type }}</dd>
                <dt>物流追踪网址</dt>
                <dd>{{ $logistics->url }}</dd>
                <dt>API对接方式</dt>
                <dd>{{ $logistics->api_docking }}</dd>
                <dt>是否启用</dt>
                <dd>{{ $logistics->is_enable == 'Y' ? '是' : '否' }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $logistics->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $logistics->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop