@extends('common.form')
@section('title') 添加图片 @stop
@section('meta')
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stop
 
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">产品</a></li>
        <li class="active"><strong>添加图片</strong></li>
    </ol>
@stop
@section('formTitle') 添加图片 @stop
@section('formAction') /productZipUpload @stop
@section('formBody')
    <div class="form-group">
    <label for="color">导入压缩包：</label>
        <input  type="file" name='zip'/>
    </div>
     
             
@stop
 
 
 
 