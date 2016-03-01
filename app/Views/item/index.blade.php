@extends('common.table')
@section('tableToolButtons')

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="sku">sku名称</th>
    <th>图片</th>
    <th>分类</th>
    <th class="sort" data-field="name">名称</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->sku }}</td>
            <td>@if($item->product->default_image>0)<img src="{{ asset($item->product->image->path) }}/{{$item->product->image->name}}" width="100px" >@else无图片@endif</td>
            <td>{{ $item->product->catalog->name }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->c_name }}</td>
            <td>{{ $item->supplier->name }}</td>
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