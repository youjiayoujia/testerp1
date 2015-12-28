@extends('layouts.default')
@section('title') 供应商详情 : {{ $supplier->name }} {{ $supplier->url }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productSupplier.index') }}">供应商</a></li>
        <li class="active"><strong>供应商详情 : {{ $supplier->name }} {{ $supplier->url }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">供应商详情 : {{ $supplier->name }} {{ $supplier->url }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $supplier->id }}</dd>
                <dt>名称</dt>
                <dd>{{ $supplier->name }}</dd>
                <dt>省</dt>
                <dd>{{ $supplier->province }}</dd>
                <dt>市</dt>
                <dd>{{ $supplier->city }}</dd>
                <dt>地址</dt>
                <dd>{{ $supplier->address }}</dd>
                <dt>是否是线上供货商</dt>
                <dd>{{ $supplier->type }}</dd>
                <dt>上线供货商网址</dt>
                <dd>{{ $supplier->url }}</dd>
                <dt>电话</dt>
                <dd>{{ $supplier->telephone }}</dd>
                <dt>采购员</dt>
                <dd>{{ $supplier->purchase_id }}</dd>
                <dt>评级</dt>
                <dd>{{ $supplier->level }}</dd>
                <dt>创建人</dt>
                <dd>{{ $supplier->created_by }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $supplier->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $supplier->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop