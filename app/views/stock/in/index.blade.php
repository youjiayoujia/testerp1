@extends('common.table')
@section('title') 入库信息列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('itemin.index') }}">入库</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 入库列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>sku</th>
    <th>数量</th>   
    <th>总金额</th>
    <th>备注</th>
    <th>仓库</th>
    <th>库位</th>
    <th>入库类型</th>
    <th>入库来源</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $itemin)
        <tr>
            <td>{{ $itemin->id }}</td>
            <td>{{ $itemin->sku }}</td>
            <td>{{ $itemin->amount}}</td>
            <td>{{ $itemin->total_amount}}</td>
            <td>{{ $itemin->remark }} </td>
            <td>{{ $itemin->getname->name }}</td>
            <td>{{ $itemin->typeof_itemin_id }}</td>
            <td>{{ $itemin->created_at }}</td>
            <td>
                <a href="{{ route('itemin.show', ['id'=>$itemin->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('itemin.edit', ['id'=>$itemin->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $itemin->id }}"
                   data-url="{{ route('itemin.destroy', ['id' => $itemin->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
