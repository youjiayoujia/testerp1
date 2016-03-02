@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>库位名</th>
    <th>所属仓库名</th>
    <th>备注信息</th>
    <th class="sort" data-field="volumn">库位容积</th>
    <th>长(cm)</th>
    <th>宽(cm)</th>
    <th>高(cm)</th>
    <th>是否启用</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $position)
        <tr>
            <td>{{ $position->id }}</td>
            <td>{{ $position->name }}</td>            
            <td>{{ $position->warehouse ? $position->warehouse->name : '' }}</td>
            <td>{{ $position->remark }} </td>            
            <td>{{ $position->size }}</td>
            <td>{{ $position->length }}</td>
            <td>{{ $position->width }}</td>
            <td>{{ $position->height }}</td>
            <td>{{ $position->is_available == 'Y' ? '是' : '否'}}</td>
            <td>{{ $position->created_at }}</td>
            <td>
                <a href="{{ route('warehousePosition.show', ['id'=>$position->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('warehousePosition.edit', ['id'=>$position->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $position->id }}"
                   data-url="{{ route('warehousePosition.destroy', ['id' => $position->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr> 
    @endforeach
@stop
