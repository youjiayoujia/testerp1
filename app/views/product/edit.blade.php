@extends('common.form')
@section('title') 编辑产品 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>编辑产品</strong></li>
    </ol>
@stop
@section('formTitle') 编辑产品 @stop
@section('formAction') {{ route('product.update', ['id' => $product->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name">名称</label>
        <input class="form-control" id="name" name='name' value="{{ old('name') ?  old('name') : $product->name }}">
    </div>
    <div class="form-group">
        <label for="c_name">中文名称</label>
        <input class="form-control" id="c_name" name='c_name' value="{{ old('c_name') ?  old('c_name') : $product->c_name }}">
    </div>
@stop