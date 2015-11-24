@extends('common.table')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="#">产品</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle')
    产品列表
@stop
@section('tableToolbar')
    @parent
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->brand->name }}</td>
            <td>{{ $product->size }}</td>
            <td>{{ $product->color }}</td>
            <td>{{ $product->created_at }}</td>
        </tr>
    @endforeach
@stop