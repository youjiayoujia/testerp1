@extends('common.table')
@section('title') 产品列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 产品列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>名称</th>
    <th>中文名称</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('product.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('product.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('product.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop