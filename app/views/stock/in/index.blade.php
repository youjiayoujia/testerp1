@extends('common.table')
@section('title') 入库信息列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('in.index') }}">入库</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 入库列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>sku</th>
    <th class="sort" data-url="{{ Sort::url('amount') }}">数量{!! Sort::label('amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('total_amount') }}">总金额{!! Sort::label('total_amount') !!}</th>
    <th>备注</th>
    <th>仓库</th>
    <th>库位</th>
    <th>入库类型</th>
    <th>入库来源</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockin)
        <tr>
            <td>{{ $stockin->id }}</td>
            <td>{{ $stockin->sku }}</td>
            <td>{{ $stockin->amount}}</td>
            <td>{{ $stockin->total_amount}}</td>
            <td>{{ $stockin->remark }} </td>
            <td>{{ $stockin->warehouses_id }}</td>
            <td>{{ $stockin->warehouse_positions_id }}</td>
            <td>{{ $stockin->typeof_stockin }}</td>
            <td>{{ $stockin->typeof_stockin_id }}</td>
            <td>{{ $stockin->created_at }}</td>
            <td>
                <a href="{{ route('in.show', ['id'=>$stockin->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('in.edit', ['id'=>$stockin->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $stockin->id }}"
                   data-url="{{ route('in.destroy', ['id' => $stockin->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
