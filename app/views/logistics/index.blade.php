@extends('common.table')
@section('title') 物流列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 物流列表 @stop
@section('tableBody')
    @foreach($data as $logistics)
        <tr>
            <td>{{ $logistics->id }}</td>
            <td>{{ $logistics->name }}</td>
            <td>{{ $logistics->country }}</td>
            <td>{{ $logistics->updated_at }}</td>
            <td>{{ $logistics->created_at }}</td>
            <td>
                <a href="{{ route('logistics.show', ['id'=>$logistics->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logistics.edit', ['id'=>$logistics->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logistics->id }}"
                   data-url="{{ route('logistics.destroy', ['id' => $logistics->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
