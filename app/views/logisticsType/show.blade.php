@extends('layouts.default')
@section('title') 物流商物流方式详情 : {{ $logisticsType->type }} {{ $logisticsType->logisticsType->name }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsType.index') }}">物流方式</a></li>
        <li class="active"><strong>物流方式详情 : {{ $logisticsType->type }} {{ $logisticsType->logisticsType->name }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">物流方式详情 : {{ $logisticsType->name }} {{ $logisticsType->logisticsType->name }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $logisticsType->id }}</dd>
                <dt>物流商物流方式</dt>
                <dd>{{ $logisticsType->type }}</dd>
                <dt>物流商</dt>
                <dd>{{ $logisticsType->logistics_id }}</dd>
                <dt>备注</dt>
                <dd>{{ $logisticsType->remark }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $logisticsType->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $logisticsType->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop