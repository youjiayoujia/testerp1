@extends('common.table')
@section('title') 物流列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 物流列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>物流商名称</th>
    <th>客户ID</th>
    <th>密钥</th>
    <th>是否有API</th>
    <th>客户经理</th>
    <th>客户经理联系方式</th>
    <th>技术人员</th>
    <th>技术联系方式</th>
    <th>备注</th>
    <th>创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logistics)
        <tr>
            <td>{{ $logistics->id }}</td>
            <td>{{ $logistics->name }}</td>
            <td>{{ $logistics->customer_id }}</td>
            <td>{{ $logistics->secret_key }}</td>
            <td>{{ $logistics->is_api == 'Y' ? '有' : '没有' }}</td>
            <td>{{ $logistics->client_manager }}</td>
            <td>{{ $logistics->manager_tel }}</td>
            <td>{{ $logistics->technician }}</td>
            <td>{{ $logistics->technician_tel }}</td>
            <td>{{ $logistics->remark }}</td>
            <td>{{ $logistics->updated_at }}</td>
            <td>{{ $logistics->created_at }}</td>
            <td>
                <a href="{{ route('logistics.show', ['id'=>$logistics->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logistics.edit', ['id'=>$logistics->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logistics->id }}"
                   data-url="{{ route('logistics.destroy', ['id' => $logistics->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
