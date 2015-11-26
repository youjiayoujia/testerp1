@extends('common.table')
@section('title') 品牌列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('brand.index') }}">品牌</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 品牌列表 @stop
@section('tableBody')
    @foreach($data as $brand)
        <tr>
            <td>{{ $brand->id }}</td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->country }}</td>
            <td>{{ $brand->updated_at }}</td>
            <td>{{ $brand->created_at }}</td>
            <td>
                <a href="{{ route('brand.show', ['id'=>$brand->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('brand.edit', ['id'=>$brand->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $brand->id }}"
                   data-url="{{ route('brand.destroy', ['id' => $brand->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
