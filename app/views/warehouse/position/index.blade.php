@extends('common.table')
@section('title') 库位列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('position.index') }}">库位</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 库位列表 @stop
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>库位名</th>
    <th>所属仓库名</th>
    <th>备注信息</th>
    <th class="sort" data-url="{{ Sort::url('volumn') }}">库位容积{!! Sort::label('volumn') !!}</th>
    <th>是否启用</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $position)
        <tr>
            <td>{{ $position->id }}</td>
            <td>{{ $position->name }}</td>            
            <td>{{ $position->warehouse->name }}</td>
            <td>{{ $position->remark }} </td>            
            <td>{{ $position->size }}</td>
            <td>{{ $position->is_available == 'Y' ? '是' : '否'}}</td>
            <td>{{ $position->created_at }}</td>
            <td>
                <a href="{{ route('position.show', ['id'=>$position->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('position.edit', ['id'=>$position->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $position->id }}"
                   data-url="{{ route('position.destroy', ['id' => $position->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr> 
    @endforeach
@stop
