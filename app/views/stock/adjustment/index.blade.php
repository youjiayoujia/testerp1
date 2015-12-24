@extends('common.table')
@section('title') 库存调整信息列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('adjustment.index') }}">库存调整</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 库存调整列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>sku</th>
    <th>类型</th>
    <th>仓库</th>
    <th>库位</th>
    <th class="sort" data-url="{{ Sort::url('amount') }}">数量{!! Sort::label('amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('total_amount') }}">总金额(￥){!! Sort::label('total_amount') !!}</th>
    <th>调整人</th>
    <th>调整时间</th>
    <th>状态</th>
    <th>审核人</th>
    <th>审核时间</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $adjustment)
        <tr>
            <td>{{ $adjustment->id }}</td>
            <td>{{ $adjustment->sku }}</td>
            <td>{{ $adjustment->type }}</td>
            <td>{{ $adjustment->warehouses_id }}</td>
            <td>{{ $adjustment->warehouse_positions_id }}</td>
            <td>{{ $adjustment->amount}}</td>
            <td>{{ $adjustment->total_amount}}</td>
            <td>{{ $adjustment->adjust_man_id }} </td>
            <td>{{ $adjustment->adjust_time }}</td>
            <td>{{ $adjustment->status }}</td>
            <td>{{ $adjustment->check_man_id }}</td>
            <td>{{ $adjustment->check_time }}</td>
            <td>{{ $adjustment->created_at }}</td>
            <td>
                <a href="{{ route('adjustment.show', ['id'=>$adjustment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('adjustment.edit', ['id'=>$adjustment->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>审核
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $adjustment->id }}"
                   data-url="{{ route('adjustment.destroy', ['id' => $adjustment->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
