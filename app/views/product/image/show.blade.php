@extends('layouts.default')
@section('title') 产品ID : {{ $image->product_id }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>产品ID : {{ $image->product_id }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">产品ID : {{ $image->product_id }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $image->id }}</dd>
                
                <dt>创建时间</dt>
                <dd>{{ $image->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $image->updated_at }}</dd>
                <dt>图片类型</dt>
                <dd>{{$image ->type }}</dd>
                <dt>已上传类型</dt>
                
                @foreach($images as $item) 
                <dd><img src="/{{$image->image_path}}{{$item}}" width="300px" height="200px"></dd>
            	@endforeach
                
               
            </dl>
        </div>
    </div>
@stop