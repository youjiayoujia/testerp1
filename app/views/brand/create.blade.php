@extends('common.form')
@section('title') 添加品牌 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('brand.index') }}">品牌</a></li>
        <li class="active"><strong>添加品牌</strong></li>
    </ol>
@stop
@section('formTitle') 添加品牌 @stop
@section('formAction') {{ route('brand.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="brand_name">品牌</label>
        <input class="form-control" id="brand_name" placeholder="品牌" name='brand_name' value="{{ old('size') }}">
    </div>
    <div class="form-group">
        <label for="country">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('size') }}">
    </div>
@stop