@extends('layouts.default')
@section('title') 入库信息详情 : {{ $stockin->sku }} {{ $stockin->typeof_stockin }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('in.index') }}">入库</a></li>
        <li class="active"><strong>入库详情 : {{ $stockin->sku }} {{ $stockin->typeof_stockin }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">入库详情 : {{ $stockin->sku }} {{ $stockin->typeof_stockin }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $stockin->id }}</dd>
                <dt>skuk</dt>
                <dd>{{ $stockin->sku }}</dd>
                <dt>数量</dt>
                <dd>{{ $stockin->amount }}</dd>
                <dt>总金额</dt>
                <dd>{{ $stockin->total_amount }}</dd>
                <dt>备注</dt>
                <dd>{{ $stockin->remark }}</dd>
                <dt>仓库</dt>
                <dd>{{ $stockin->warehouses_id }}</dd>
                <dt>库位</dt>
                <dd>{{ $stockin->warehouse_positions_id }}</dd>
                <dt>入库类型</dt>
                <dd>{{ $stockin->typeof_stockin }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $stockin->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $stockin->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop