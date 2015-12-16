@extends('common.form')
@section('title') 添加产品 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>添加产品</strong></li>
    </ol>
@stop
@section('formTitle') 添加产品 @stop
@section('formAction') {{ route('product.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="name">名称</label>
        <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="c_name">中文名称</label>
        <input class="form-control" id="c_name" placeholder="中文名称" name='c_name' value="{{ old('c_name') }}">
    </div>
@stop