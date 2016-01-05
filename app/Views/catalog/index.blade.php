@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>名称</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">更新时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $catalog)
        <tr>
            <td>{{ $catalog->id }}</td>
            <td>{{ $catalog->name }}</td>
            <td>{{ $catalog->updated_at }}</td>
            <td>{{ $catalog->created_at }}</td>
            <td>
                <a href="{{ route('catalog.show', ['id'=>$catalog->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
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
