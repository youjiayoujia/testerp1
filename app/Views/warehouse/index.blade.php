@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>省</th>   
    <th>市</th>
    <th>详细地址</th>
    <th>联系人</th>
    <th>联系电话</th>
    <th>类型</th>
    <th class="sort" data-field="volumn">容积</th>
    <th>是否启用</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $warehouse)
        <tr>
            <td>{{ $warehouse->id }}</td>
            <td>{{ $warehouse->name }}</td>
            <td>{{ $warehouse->province}}</td>
            <td>{{ $warehouse->city}}</td>
            <td>{{ $warehouse->address }}</td>
            <td>{{ $warehouse->contactByName ? $warehouse->contactByName->name : ''}}</td>
            <td>{{ $warehouse->telephone }}</td>
            <td>{{ $warehouse->type == 'local' ? '本地仓库' : ($warehouse->type == 'oversea' ? '海外仓库' : '第三方仓库') }} </td>
            <td>{{ $warehouse->volumn }}(m<small>3</small>)</td>
            <td>{{ $warehouse->is_available == '1' ? '是' : '否'}}</td>
            <td>{{ $warehouse->created_at }}</td>
            <td>
                <a href="{{ route('warehouse.show', ['id'=>$warehouse->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('warehouse.edit', ['id'=>$warehouse->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $warehouse->id }}"
                   data-url="{{ route('warehouse.destroy', ['id' => $warehouse->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
