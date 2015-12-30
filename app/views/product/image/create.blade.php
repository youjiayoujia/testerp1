 
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
@section('formAction')  /productUpload @stop
@section('formBody')
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input type="hidden" name="user_id" value="1">
    <div class="form-group">
        <label for="brand_id">产品ID:</label>
        <input  type="text" name='product_id'  value=''/>
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
        <label for="color">图片类型：</label>
         <select id="brand_id" class="form-control" name="type">
            @foreach($image_type as $item) 
                <option value="{{ $item }}" >{{ $item }}</option>
            @endforeach
        </select>
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
 
 
 
 