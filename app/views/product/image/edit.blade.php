 
@extends('common.form')
@section('title') 修改图片 @stop
@section('meta')
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stop
 
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productImage.index') }}">产品图片</a></li>
        <li class="active"><strong>修改图片</strong></li>
    </ol>
@stop
@section('formTitle') 添加图片 @stop
@section('formAction')  /productUpdate @stop
@section('formBody')
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input  type="hidden" name='id'  value='{{$image->id}}'/>
    <div class="form-group">
        <label for="brand_id">产品ID:</label>
        <input  type="text" name='product_id'  value='{{$image->product_id}}'/>
    </div>	
   <div class="form-group"  >
        <label for="color">图片类型：</label>
         <select id="brand_id" class="form-control" name="type">
            @foreach($imageType as $item) 
                <option value="{{ $item }}" {{ $item == $image->type ? 'selected' : ''}}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
     <div class="form-group"  >
    <label for="color">已有图片：</label></br>
     <div style="float:left">
        <img src="/{{$image->path}}{{$image->name}}" width="300px" height="200px" ></br>
     </div>
 
     <p style="clear:both"></p>      
    </div>                   
    <div class="form-group">
    <label for="color">更改图片：</label>
        <input   name='map' type='file' />
    </div>
   
       
@stop
 
 
 
 