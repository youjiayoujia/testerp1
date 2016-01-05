@extends('layouts.default')
@section('title') 产品详情 : {{ $product->name }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>产品详情 : {{ $product->name }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">产品详情 : {{ $product->name }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $product->id }}</dd>
                
                <dt>创建时间</dt>
                <dd>{{ $product->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $product->updated_at }}</dd>
                <dt>图片</dt>
                <dd><img src="{{ asset('storage/uploads/product/4/4defaults.jpg')}}"></dd>
                <dt>已上传类型</dt>
                
                @foreach($product_imageType as $item) 
                <dd>{{ $item ->type}}</dd>
            	@endforeach
                
               
            </dl>
        </div>
    </div>
@stop