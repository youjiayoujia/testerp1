@extends('layouts.default')
@section('title') 品类详情 : {{ $catalog->name }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('catalog.index') }}">品类</a></li>
        <li class="active"><strong>品类详情 : {{ $catalog->name }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">品类详情 : {{ $catalog->name }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>名称</dt>
                <dd>{{ $catalog->name }}</dd>
            </dl>
        </div>
    </div>
@stop