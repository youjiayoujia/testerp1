@extends('common.form')
@section('title') 添加图片 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>添加图片</strong></li>
    </ol>
@stop
@section('formTitle') 添加图片 @stop
@section('formAction') {{ route('product.update', ['id' => $product->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="brand_id">产品名</label>
         
    </div>
    <div class="form-group">
        <label for="size">型号</label>
        <input class="form-control" id="size" placeholder="型号" name='size' value="{{ old('size') ?  old('size') : $product->size }}">
    </div>
    <div class="form-group">
        <label for="color">颜色</label>
        <input class="form-control" id="color" placeholder="颜色" name='color' value="{{ old('color') ?  old('color') : $product->color }}">
    </div>
@stop
  