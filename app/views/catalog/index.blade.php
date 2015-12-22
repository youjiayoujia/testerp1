@extends('common.table')
@section('title') 品类列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('catalog.index') }}">品类</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 品类列表 @stop
@section('tableHeader')
    <th class="sort" data-url="{{ App\Helps\Sort::url('id') }}">
        ID{!! App\Helps\Sort::label('id') !!}
    </th>
    <th class="sort" data-url="{{ App\Helps\Sort::url('name') }}">
        名称{!! App\Helps\Sort::label('name') !!}
    </th>
    <th class="sort" data-url="{{ App\Helps\Sort::url('created_at') }}">
        创建时间{!! App\Helps\Sort::label('created_at') !!}
    </th>
    <th data-sort="false">操作</th>
@stop
@section('tableBody')
    @foreach($data as $catalog)
        <tr>
            <td>{{ $catalog->id }}</td>
            <td>{{ $catalog->name }}</td>
            <td>{{ $catalog->created_at }}</td>
            <td>
                <a href="{{ route('catalog.show', ['id'=>$catalog->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('catalog.edit', ['id'=>$catalog->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $catalog->id }}"
                   data-url="{{ route('catalog.destroy', ['id' => $catalog->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop