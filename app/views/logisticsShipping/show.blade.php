@extends('layouts.default')
@section('title') 物流方式shippings详情 : {{ $logisticsShipping->short_code }} {{ $logisticsShipping->logistics_type }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsShipping.index') }}">物流方式shippings</a></li>
        <li class="active"><strong>物流方式shippings详情 : {{ $logisticsShipping->short_code }} {{ $logisticsShipping->logistics_type }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">物流方式shippings详情 : {{ $logisticsShipping->short_code }} {{ $logisticsShipping->logistics_type }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $logisticsShipping->id }}</dd>
                <dt>物流方式简码</dt>
                <dd>{{ $logisticsShipping->short_code }}</dd>
                <dt>物流方式名称</dt>
                <dd>{{ $logisticsShipping->logistics_type }}</dd>
                <dt>种类</dt>
                <dd>{{ $logisticsShipping->species }}</dd>
                <dt>仓库</dt>
                <dd>{{ $logisticsShipping->warehouse }}</dd>
                <dt>物流商</dt>
                <dd>{{ $logisticsShipping->logistics->name }}</dd>
                <dt>物流商物流方式</dt>
                <dd>{{ $logisticsShipping->logisticsType->type }}</dd>
                <dt>物流追踪网址</dt>
                <dd>{{ $logisticsShipping->url }}</dd>
                <dt>API对接方式</dt>
                <dd>{{ $logisticsShipping->api_docking }}</dd>
                <dt>是否启用</dt>
                <dd>{{ $logisticsShipping->is_enable == 'Y' ? '是' : '否' }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $logisticsShipping->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $logisticsShipping->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop