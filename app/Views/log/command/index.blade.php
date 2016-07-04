@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>关联ID</th>
    <th>名称</th>
    <th>描述</th>
    <th>执行时间</th>
    <th>执行结果</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">创建时间</th>
@stop
@section('tableBody')
    @foreach($data as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->relation_id }}</td>
            <td>{{ $log->signature }}</td>
            <td>{{ $log->description }}</td>
            <td>{{ $log->lasting }}秒</td>
            <td>{{ $log->result }}</td>
            <td>{{ $log->remark }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
    @endforeach
@stop
@section('tableToolbar')
@stop