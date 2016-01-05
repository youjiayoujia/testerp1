@extends('layouts.default')
@section('breadcrumbs')
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>图片详情</strong></li>
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
                <dt>已上传图片</dt>
                <dd><img src="/{{$image->path}}{{$image->name}}" width="300px" height="200px"></dd>               
            </dl>
        </div>
    </div>
@stop