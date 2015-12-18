@extends('common.table')
@section('title') 物流商物流方式列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsType.index') }}">物流方式</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 物流商物流方式列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>物流商物流方式</th>
    <th>物流商</th>
    <th>备注</th>
    <th>创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logisticsType)
        <tr>
            <td>{{ $logisticsType->id }}</td>
            <td>{{ $logisticsType->type }}</td>
            <td>{{ $logisticsType->logisticsType->name ? $logisticsType->logisticsType->name : '' }}</td>
            <td>{{ $logisticsType->remark }}</td>
            <td>{{ $logisticsType->created_at }}</td>
            <td>{{ $logisticsType->updated_at }}</td>
            <td>
                <a href="{{ route('logisticsType.show', ['id'=>$logisticsType->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsType.edit', ['id'=>$logisticsType->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logisticsType->id }}"
                   data-url="{{ route('logisticsType.destroy', ['id' => $logisticsType->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
