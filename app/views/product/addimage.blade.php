 
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
@section('formAction')  /product/image_update @stop
@section('formBody')
    <!-- <input type="hidden" name="_method" value="POST"/>
    <input type="hidden" name="_enctype" value="multipart/form-data"/>
   <input type="hidden" name="_token" value="{{ csrf_token() }}">-->
    <div class="form-group">
        <label for="brand_id">产品名:{{$product->size}}</label>
        <input  type="hidden" name='product_id'  value='{{$product->id}}'/>
    </div>	
    <div class="form-group">
        <label for="size">供应商提供的URL：</label>
        <input  class="form-control" id="size" placeholder="供应商提供的RUL" name='suppliers_url'  />
    </div>
    <div class="form-group">
        <label for="color">图片类型：</label>
         <select id="brand_id" class="form-control" name="type">
            @foreach($image_type as $item) 
                <option value="{{ $item }}" >{{ $item }}</option>
            @endforeach
        </select>
    </div>           
    <div class="form-group">
    <label for="color">上传图片：</label>
        <input   name='map0' type='file' />
        <input   name='map1' type='file' />
        <input   name='map2' type='file' />
        <input   name='map3' type='file' />
        <input   name='map4' type='file' />
        <input   name='map5' type='file' />
    </div>
    <div class="form-group">
    <label for="color">导入压缩包：</label>
        <input  type="file" name='zip'/>
    </div>
     
             
@stop
 
 
 
 