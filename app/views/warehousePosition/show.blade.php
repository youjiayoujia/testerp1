@extends('layouts.default')
@section('title') 库位详情 : {{ $warehousePosition->warehouse->name }} {{ $warehousePosition->name }}  @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehousePosition.index') }}">库位</a></li>
        <li class="active"><strong>库位详情 : {{ $warehousePosition->warehouse->name }} {{ $warehousePosition->name }} </strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">库位详情 : {{ $warehousePosition->warehouse->name }} {{ $warehousePosition->name }} </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $warehousePosition->id }}</dd>
                <dt>名称</dt>
                <dd>{{ $warehousePosition->name }}</dd>
                <dt>详细地址</dt>
                <dd>{{ $warehousePosition->warehouse->name }}</dd>
                <dt>类型</dt>
                <dd>{{ $warehousePosition->remark }}</dd>
                <dt>容积</dt>
                <dd>{{ $warehousePosition->size }}</dd>
                <dt>是否启用</dt>
                <dd>{{ $warehousePosition->is_available == 'Y' ? '是' : '否' }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $warehousePosition->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $warehousePosition->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop