@extends('layouts.default')
@section('title') 库存信息详情 : {{ $stock->sku }} {{ $stock->item_id }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stock.index') }}">库存</a></li>
        <li class="active"><strong>库存详情 : {{ $stock->sku }} {{ $stock->item_id }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">库存详情 : {{ $stock->sku }} {{ $stock->item_id }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $stock->id }}</dd>
                <dt>item号</dt>
                <dd>{{ $stock->item_id }}</dd>
                <dt>sku</dt>
                <dd>{{ $stock->sku }}</dd>
                <dt>仓库</dt>
                <dd>{{ $stock->warehouse->name }}</dd>
                <dt>库位</dt>
                <dd>{{ $stock->position->name }}</dd>
                <dt>总数量</dt>
                <dd>{{ $stock->all_amount }}</dd>
                <dt>可用数量</dt>
                <dd>{{ $stock->available_amount }}</dd>
                <dt>hold数量</dt>
                <dd>{{ $stock->hold_amount }}</dd>
                <dt>总金额(￥)</dt>
                <dd>{{ $stock->total_amount }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $stock->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $stock->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop