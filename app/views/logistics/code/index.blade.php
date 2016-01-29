@extends('common.table')
@section('tableHeader')
    <th class="sort">ID</th>
    <th class="sort">物流方式</th>
    <th class="sort">跟踪号</th>
    <th class="sort">包裹ID</th>
    <th>状态</th>
    <th class="sort">使用时间</th>
    <th class="sort">创建时间</th>
    <th class="sort">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $code)
        <tr>
            <td>{{ $code->id }}</td>
            <td>{{ $code->logistics->logistics_type}}</td>
            <td>{{ $code->code }}</td>
            <td>{{ $code->package_id }}</td>
            <td>{{ $code->status == 'Y' ? '启用' : '未启用'}}</td>
            <td>{{ $code->used_at }}</td>
            <td>{{ $code->updated_at }}</td>
            <td>{{ $code->created_at }}</td>
            <td>
                <a href="{{ route('logisticsCode.show', ['id'=>$code->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsCode.edit', ['id'=>$code->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $code->id }}"
                   data-url="{{ route('logisticsCode.destroy', ['id' => $code->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
