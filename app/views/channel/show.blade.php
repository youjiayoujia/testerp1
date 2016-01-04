@extends('layouts.default')
@section('title') 渠道详情 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
            <li><a href="{{ route('channel.index') }}">渠道</a></li>
        <li class="active"><strong>渠道详情</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">渠道详情</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $channel->id }}</dd>
                <dt>名称</dt>
                <dd>{{ $channel->name }}</dd>
                <dt>别名</dt>
                <dd>{{ $channel->alias }}</dd>
                <dt>创建者</dt>
                <dd>{{ $channel->created_by }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $channel->created_at }}</dd>
                <dt>更新者</dt>
                <dd>{{ $channel->updated_by }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $channel->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop