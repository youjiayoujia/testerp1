@extends('common.table')
@section('title') 库位列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehousePosition.index') }}">库位</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 库位列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>库位名</th>
    <th>所属仓库名</th>
    <th>备注信息</th>
    <th>库位容积</th>
    <th>是否启用</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $warehousePosition)
        <tr>
            <td>{{ $warehousePosition->id }}</td>
            <td>{{ $warehousePosition->name }}</td>            
            <td>{{ $warehousePosition->warehouse->name }}</td>
            <td>{{ $warehousePosition->remark }} </td>            
            <td>{{ $warehousePosition->size }}</td>
            <td>{{ $warehousePosition->is_available == 'Y' ? '是' : '否'}}</td>
            <td>{{ $warehousePosition->created_at }}</td>
            <td>
                <a href="{{ route('warehousePosition.show', ['id'=>$warehousePosition->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('warehousePosition.edit', ['id'=>$warehousePosition->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $warehousePosition->id }}"
                   data-url="{{ route('warehousePosition.destroy', ['id' => $warehousePosition->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr> 
    @endforeach
@stop
