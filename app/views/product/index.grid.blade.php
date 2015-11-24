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

@section('gridColumns')
    [{
    field: 'id',
    title: '#',
    sortable: true
    },{
    field: 'brand_name',
    title: '品牌'
    },{
    field: 'size',
    title: '型号',
    sortable: true
    },{
    field: 'color',
    title: '颜色',
    sortable: true
    }]
@stop