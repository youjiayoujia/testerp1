@extends('layouts.default')
@section('title') 渠道账号详情 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
            <li><a href="{{ route('channelAccount.index') }}">渠道账号</a></li>
        <li class="active"><strong>渠道账号详情</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">渠道账号详情</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $account->id }}</dd>
                <dt>渠道</dt>
                <dd>{{ $account->channel->name }}</dd>
                <dt>名称</dt>
                <dd>{{ $account->title }}</dd>
                <dt>账号</dt>
                <dd>{{ $account->account }}</dd>
                <dt>前缀</dt>
                <dd>{{ $account->prefix }}</dd>
                <dt>国家</dt>
                <dd>{{ $account->country }}</dd>
                <dt>币种</dt>
                <dd>{{ $account->currency }}</dd>
                <dt>简介</dt>
                <dd>{{ $account->brief }}</dd>
                <dt>创建者</dt>
                <dd>{{ $account->created_by }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $account->created_at }}</dd>
                <dt>更新者</dt>
                <dd>{{ $account->updated_by }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $account->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop