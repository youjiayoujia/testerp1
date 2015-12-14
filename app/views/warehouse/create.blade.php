@extends('common.form')
@section('title') 添加仓库 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehouse.index') }}">仓库</a></li>
        <li class="active"><strong>添加仓库</strong></li>
    </ol>
@stop
@section('formTitle') 添加仓库 @stop
@section('formAction') {{ route('warehouse.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="warehouse_name">仓库</label>
        <input class="form-control" id="warehouse_name" placeholder="仓库" name='warehouse_name' value="{{ old('size') }}">
    </div>
    <div class="form-group">
        <label for="country">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('size') }}">
    </div>
@stop