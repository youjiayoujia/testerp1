@extends('layouts.default')
@section('title') 品牌详情 : {{ $brand->name }} {{ $brand->country }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('brand.index') }}">品牌</a></li>
        <li class="active"><strong>品牌详情 : {{ $brand->name }} {{ $brand->country }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">品牌详情 : {{ $brand->name }} {{ $brand->country }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $brand->id }}</dd>
                <dt>品牌</dt>
                <dd>{{ $brand->name }}</dd>
                <dt>国家</dt>
                <dd>{{ $brand->country }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $brand->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $brand->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop