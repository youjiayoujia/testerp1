@extends('layouts.default')
@section('title') 出库信息详情 : {{ $stockout->sku }} {{ $stockout->typeof_stockout }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockOut.index') }}">出库</a></li>
        <li class="active"><strong>出库详情 : {{ $stockout->sku }} {{ $stockout->typeof_stockout }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">出库详情 : {{ $stockout->sku }} {{ $stockout->typeof_stockout }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $stockout->id }}</dd>
                <dt>skuk</dt>
                <dd>{{ $stockout->sku }}</dd>
                <dt>数量</dt>
                <dd>{{ $stockout->amount }}</dd>
                <dt>总金额</dt>
                <dd>{{ $stockout->total_amount }}</dd>
                <dt>备注</dt>
                <dd>{{ $stockout->remark }}</dd>
                <dt>仓库</dt>
                <dd>{{ $stockout->warehouse->name }}</dd>
                <dt>库位</dt>
                <dd>{{ $stockout->position->name }}</dd>
                <dt>出库类型</dt>
                <dd>{{ $stockout->type }}</dd>
                <dt>出库类型id</dt>
                <dd>{{ $stockout->relation_id }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $stockout->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $stockout->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop