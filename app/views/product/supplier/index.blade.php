@extends('common.table')
@section('title') 供货商列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('supplier.index') }}">供货商</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 供货商列表 @stop
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>名称</th>
    <th>省</th>
    <th>市</th>
    <th>经纬度</th>
    <th>是否是线上供货商</th>
    <th>线上供货商网址</th>
    <th class="sort" data-url="{{ Sort::url('telephone') }}">电话{!! Sort::label('telephone') !!}</th>
    <th>采购员</th>
    <th class="sort" data-url="{{ Sort::url('level') }}">评级{!! Sort::label('level') !!}</th>
    <th>创建人</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $supplier)
        <tr>
            <td>{{ $supplier->id }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->province}}</td>
            <td>{{ $supplier->city}}</td>
            <td>{{ $supplier->address }}</td>
            <td>{{ $supplier->type }} </td>
            <td>{{ $supplier->url }}</td>
            <td>{{ $supplier->telephone }}</td>
            <td>{{ $supplier->purchase_id }}</td>
            <td>{{ $supplier->level }}</td>
            <td>{{ $supplier->created_by}}</td>
            <td>{{ $supplier->created_at }}</td>
            <td>
                <a href="{{ route('supplier.show', ['id'=>$supplier->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('supplier.edit', ['id'=>$supplier->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $supplier->id }}"
                   data-url="{{ route('supplier.destroy', ['id' => $supplier->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
