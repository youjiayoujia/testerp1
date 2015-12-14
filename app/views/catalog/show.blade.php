@extends('layouts.default')
@section('title') 产品详情 : {{ $product->brand->name }} {{ $product->size }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>产品详情 : {{ $product->brand->name }} {{ $product->size }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">产品详情 : {{ $product->brand->name }} {{ $product->size }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $product->id }}</dd>
                <dt>品牌</dt>
                <dd>{{ $product->brand->name }}</dd>
                <dt>型号</dt>
                <dd>{{ $product->size }}</dd>
                <dt>颜色</dt>
                <dd>{{ $product->color }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $product->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $product->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop