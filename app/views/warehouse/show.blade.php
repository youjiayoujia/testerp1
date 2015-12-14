@extends('layouts.default')
@section('title') 仓库详情 : {{ $warehouse->name }} {{ $warehouse->country }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehouse.index') }}">仓库</a></li>
        <li class="active"><strong>仓库详情 : {{ $warehouse->name }} {{ $warehouse->country }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">仓库详情 : {{ $warehouse->name }} {{ $warehouse->country }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $warehouse->id }}</dd>
                <dt>仓库</dt>
                <dd>{{ $warehouse->name }}</dd>
                <dt>国家</dt>
                <dd>{{ $warehouse->country }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $warehouse->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $warehouse->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop