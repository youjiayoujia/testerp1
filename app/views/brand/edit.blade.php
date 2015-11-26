@extends('common.form')
@section('title') 编辑品牌 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('brand.index') }}">品牌</a></li>
        <li class="active"><strong>编辑品牌</strong></li>
    </ol>
@stop
@section('formTitle') 编辑产品 @stop
@section('formAction') {{ route('brand.update', ['id' => $brand->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="size">品牌</label>
        <input class="form-control" id="brand_name" placeholder="国家" name='brand_name' value="{{ old('name') ?  old('name') : $brand->name }}">
    </div>
    <div class="form-group">
        <label for="size">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('country') ?  old('country') : $brand->country }}">
    </div>
@stop