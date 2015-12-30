 
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
    <div class="form-group">
        <label for="URL">供应商提供的URL：</label>
        <input  class="form-control" id="url" placeholder="供应商提供的RUL" name='suppliers_url'  />
    </div>
     <div class="form-group" id='checktype'>
        <label for="brand_id">选择上传类型:</label>
        <input  type="radio" name='uploadType'  value='image' checked onClick="checktype();"/>上传图片
        <input  type="radio" name='uploadType'  value='zip' onClick="checktype();"/>上传压缩包
    </div>
    <div class="form-group"  >
    <label for="color">修改图片类型：</label>
    <input  class="form-control" id="url" placeholder="图片类型" name="type" value='{{$image->type}}' />      
    </div>
     <div class="form-group"  >
    <label for="color">已有图片：</label></br>
     @foreach($images as $item) 
     <div style="float:left">
        <img src="/{{$image->image_path}}{{$item}}" width="300px" height="200px" ></br>
     	<a href="/imageDelete/{{$image->id}}/{{$item}}">删除该图</a>
     </div>
     @endforeach
     <p style="clear:both"></p>      
    </div>           
   <div id='imagediv'>          
    <div class="form-group">
    <label for="color">上传图片：</label>
        <input   name='map0' type='file' />
        <input   name='map1' type='file' />
        <input   name='map2' type='file' />
        <input   name='map3' type='file' />
        <input   name='map4' type='file' />
        <input   name='map5' type='file' />
    </div>
    </div>
    <div style="display:none" id='zipdiv'>
    <div class="form-group">
    <label for="color">导入压缩包：</label>
        <input  type="file" name='zip'/>
    </div>
    </div>
  <script type="text/javascript">
  function checktype(){
  var uploadType=$("#checktype [name='uploadType']:checked").val();
 	if(uploadType == 'image'){
		$('#zipdiv').hide();
		$('#imagediv').show();
		}else{
		$('#imagediv').hide();
		$('#zipdiv').show();		
			}
  //alert (uploadType);
  }
  </script>       
@stop
 
 
 
 