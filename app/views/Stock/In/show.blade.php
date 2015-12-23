@extends('layouts.default')
@section('title') 入库信息详情 : {{ $itemin->sku }} {{ $itemin->getname->name }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('itemin.index') }}">入库</a></li>
        <li class="active"><strong>入库详情 : {{ $itemin->sku }} {{ $itemin->getname->name }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">入库详情 : {{ $itemin->sku }} {{ $itemin->getname->name }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $itemin->id }}</dd>
                <dt>skuk</dt>
                <dd>{{ $itemin->sku }}</dd>
                <dt>数量</dt>
                <dd>{{ $itemin->amount }}</dd>
                <dt>总金额</dt>
                <dd>{{ $itemin->total_amount }}</dd>
                <dt>备注</dt>
                <dd>{{ $itemin->remark }}</dd>
                <dt>入库类型</dt>
                <dd>{{ $itemin->getname->name }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $itemin->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $itemin->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop