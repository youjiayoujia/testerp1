@extends('common.grid')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="#">产品</a></li>
        <li class="active"><strong>Jquery列表</strong></li>
    </ol>
@stop

@section('gridConfig') @parent @stop
@section('gridToolbar') @parent @stop