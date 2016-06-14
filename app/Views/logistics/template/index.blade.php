@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">编号</th>
    <th>面单名称</th>
    <th>视图</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $template)
        <tr>
            <td>{{ $template->id }}</td>
            <td>{{ $template->name }}</td>
            <td>{{ $template->view }}</td>
            <td>{{ $template->updated_at }}</td>
            <td>{{ $template->created_at }}</td>
            <td>
                <a href="{{ route('view', ['id'=>$template->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 面单模版
                </a>
                <a href="{{ route('logisticsTemplate.show', ['id'=>$template->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsTemplate.edit', ['id'=>$template->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $template->id }}"
                   data-url="{{ route('logisticsTemplate.destroy', ['id' => $template->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
