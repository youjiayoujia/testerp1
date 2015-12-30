@extends('common.table')
@section('title') 产品列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productImage.index') }}">产品</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 产品列表 @stop
@section('tableHeader')
    <th>ID</th>
    <th>产品ID</th>
    <th>图片类型</th>
    <th>上传时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $image)
        <tr>
            <td>{{ $image->id }}</td>
            <td>{{ $image->product_id}}</td>
            <td>{{ $image->type }}</td>
            <td>{{ $image->created_at }}</td>
            <td>
                <a href="{{ route('productImage.show', ['id'=>$image->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('productImage.edit', ['id'=>$image->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $image->id }}"
                   data-url="{{ route('productImage.destroy', ['id' =>$image->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop