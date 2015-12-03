@extends('layouts.default')
@section('title') 选款需求详情 : {{ $fashion->name }} {{ $fashion->address }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('fashion.index') }}">供应商</a></li>
        <li class="active"><strong>选款需求详情 : {{ $fashion->name }} {{ $fashion->address }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">选款需求详情 : {{ $fashion->name }} {{ $fashion->address }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $fashion->id }}</dd>
                <dt>选款需求名</dt>
                <dd>{{ $fashion->name }}</dd>
                <dt>货物源地址</dt>
                <dd>{{ $fashion->address }}</dd>
                <dt>类似款sku</dt>
                <dd>{{ $fashion->similar_sku }}</dd>
                <dt>竞争产品url</dt>
                <dd>{{ $fashion->competition_url }}</dd>
                <dt>选款备注</dt>
                <dd>{{ $fashion->remark }}</dd>
                <dt>期待上传日期</dt>
                <dd>{{ $fashion->expected_date }}</dd>
                <dt>需求人id</dt>
                <dd>{{ $fashion->needer_id }}</dd>
                <dt>需求店铺id</dt>
                <dd>{{ $fashion->needer_shopid }}</dd>
                <dt>处理状态</dt>
                <dd>{{ $fashion->status }}</dd>
                <dt>处理者id</dt>
                <dd>{{ $fashion->user_id }}</dd>
                <dt>处理时间</dt>
                <dd>{{ $fashion->handle_time }}</dd>
            </dl>
        </div>
    </div>
@stop