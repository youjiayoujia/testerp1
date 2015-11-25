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
        <label for="brand_id">品牌</label>
        <select id="brand_id" class="form-control" name="brand_id">
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ $brand->id == old('brand_id') ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="size">型号</label>
        <input class="form-control" id="size" placeholder="型号" name='size' value="{{ old('size') }}">
    </div>
    <div class="form-group">
        <label for="color">颜色</label>
        <input class="form-control" id="color" placeholder="颜色" name='color' value="{{ old('color') }}">
    </div>
@stop