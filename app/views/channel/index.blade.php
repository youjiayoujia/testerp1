@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>名称</th>
    <th>别名</th>
    <th>创建者</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>更新者</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $channel)
        <tr>
            <td>{{ $channel->id }}</td>
            <td>{{ $channel->name }}</td>
            <td>{{ $channel->alias }}</td>
            <td>{{ $channel->created_by }}</td>
            <td>{{ $channel->created_at }}</td>
            <td>{{ $channel->updated_by }}</td>
            <td>{{ $channel->updated_at }}</td>
            <td>
                <a href="{{ route('channel.show', ['id'=>$channel->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('channel.edit', ['id'=>$channel->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $channel->id }}"
                   data-url="{{ route('channel.destroy', ['id' => $channel->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
