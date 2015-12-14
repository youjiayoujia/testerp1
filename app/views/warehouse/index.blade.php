@extends('common.table')
@section('title') 仓库列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehouse.index') }}">仓库</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 仓库列表 @stop
@section('tableBody')
    @foreach($data as $warehouse)
        <tr>
            <td>{{ $warehouse->id }}</td>
            <td>{{ $warehouse->name }}</td>
            <td>{{ $warehouse->country }}</td>
            <td>{{ $warehouse->updated_at }}</td>
            <td>{{ $warehouse->created_at }}</td>
            <td>
                <a href="{{ route('warehouse.show', ['id'=>$warehouse->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('warehouse.edit', ['id'=>$warehouse->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $warehouse->id }}"
                   data-url="{{ route('warehouse.destroy', ['id' => $warehouse->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
