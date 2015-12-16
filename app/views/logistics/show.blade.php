@extends('layouts.default')
@section('title') 物流详情 : {{ $logistics->name }} {{ $logistics->customer_id }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active"><strong>物流详情 : {{ $logistics->name }} {{ $logistics->customer_id }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">物流详情 : {{ $logistics->name }} {{ $logistics->customer_id }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $logistics->id }}</dd>
                <dt>物流商名称</dt>
                <dd>{{ $logistics->name }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>密钥</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $logistics->customer_id }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $logistics->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $logistics->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop