@extends('common.table')
@section('title') 物流方式shippings列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsShipping.index') }}">物流方式shippings</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 物流方式shippings列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>物流方式简码</th>
    <th>物流方式名称</th>
    <th>种类</th>
    <th>仓库</th>
    <th>物流商</th>
    <th>物流商物流方式</th>
    <th>物流追踪网址</th>
    <th>API对接方式</th>
    <th>是否启用</th>
    <th>创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logisticsShipping)
        <tr>
            <td>{{ $logisticsShipping->id }}</td>
            <td>{{ $logisticsShipping->short_code }}</td>
            <td>{{ $logisticsShipping->logistics_type }}</td>
            <td>{{ $logisticsShipping->species }}</td>
            <td>{{ $logisticsShipping->warehouse }}</td>
            <td>{{ $logisticsShipping->logistics->name ? $logisticsShipping->logistics->name : '' }}</td>
            <td>{{ $logisticsShipping->logisticsType->type ? $logisticsShipping->logisticsType->type : '' }}</td>
            <td>{{ $logisticsShipping->url }}</td>
            <td>{{ $logisticsShipping->api_docking }}</td>
            <td>{{ $logisticsShipping->is_enable == 'Y' ? '是' : '否' }}</td>
            <td>{{ $logisticsShipping->created_at }}</td>
            <td>{{ $logisticsShipping->updated_at }}</td>
            <td>
                <a href="{{ route('logisticsShipping.show', ['id'=>$logisticsShipping->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsShipping.edit', ['id'=>$logisticsShipping->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logisticsShipping->id }}"
                   data-url="{{ route('logisticsShipping.destroy', ['id' => $logisticsShipping->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
