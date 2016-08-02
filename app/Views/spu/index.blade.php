@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>图片</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $spu)
        <tr>
            <td>{{ $spu->id }}</td>
            <td>{{ $spu->spu }}</td>
            <td>{{ $spu->spu }}</td>
            <td>{{ $spu->updated_at }}</td>
            <td>{{ $spu->created_at }}</td>
            <td>
                <a href="{{ route('spu.show', ['id'=>$spu->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('spu.edit', ['id'=>$spu->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="{{ route('createSpuImage', ['spu_id'=>$spu->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $spu->id }}"
                   data-url="{{ route('spu.destroy', ['id' => $spu->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach

@stop
