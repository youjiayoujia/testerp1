@extends('common.table')
@section('tableToolButtons')

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="sku">sku名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                <a href="{{ route('item.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('item.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('item.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach

@stop