@extends('layouts.default')
@section('title') 库存调整信息详情 : {{ $adjustment->sku }} {{ $adjustment->type }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockAdjustment.index') }}">库存调整</a></li>
        <li class="active"><strong>库存调整详情 : {{ $adjustment->sku }} {{ $adjustment->type }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">库存调整详情 : {{ $adjustment->sku }} {{ $adjustment->type }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $adjustment->id }}</dd>
                <dt>sku</dt>
                <dd>{{ $adjustment->sku }}</dd>
                <dt>类型</dt>
                <dd>{{ $adjustment->type }}</dd>
                <dt>仓库</dt>
                <dd>{{ $adjustment->warehouses_id }}</dd>
                <dt>库位</dt>
                <dd>{{ $adjustment->warehouse_positions_id }}</dd>
                <dt>数量</dt>
                <dd>{{ $adjustment->amount }}</dd>
                <dt>金额</dt>
                <dd>{{ $adjustment->total_amount }}</dd>
                <dt>调整人</dt>
                <dd>{{ $adjustment->adjust_man_id }}</dd>
                <dt>调整时间</dt>
                <dd>{{ $adjustment->adjust_time }}</dd>
                <dt>审核状态</dt>
                <dd>{{ $adjustment->status }}</dd>
                <dt>审核人</dt>
                <dd>{{ $adjustment->check_man_id }}</dd>
                <dt>审核时间</dt>
                <dd>{{ $adjustment->check_time }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $adjustment->created_at }}</dd>
            </dl>
        </div>
    </div>
@stop