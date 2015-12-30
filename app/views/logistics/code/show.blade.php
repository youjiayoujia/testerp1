@extends('layouts.default')
@section('title') 跟踪号号码池详情 : {{ $code->logistics_id }} {{ $code->code }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsCode.index') }}">跟踪号号码池</a></li>
        <li class="active"><strong>跟踪号号码池详情 : {{ $code->logistics_id }} {{ $code->code }}</strong></li>
    </ol>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">跟踪号号码池详情 : {{ $code->logistics_id }} {{ $code->code }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $code->id }}</dd>
                <dt>物流方式</dt>
                <dd>{{ $code->logistics_id }}</dd>
                <dt>跟踪号</dt>
                <dd>{{ $code->code }}</dd>
                <dt>包裹ID</dt>
                <dd>{{ $code->package_id }}</dd>
                <dt>状态</dt>
                <dd>{{ $code->status == 'Y' ? '启用' : '未启用'}}</dd>
                <dt>使用时间</dt>
                <dd>{{ $code->used_at }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $code->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $code->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop