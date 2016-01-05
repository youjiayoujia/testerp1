@extends('common.form')
@section('formAction') /productUpload @stop
@section('formBody')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="1">
    <div class="form-group col-lg-12">
        <label class='control-label'>产品ID</label>
        <input class="form-control" type="text" name='product_id' value=''/>
    </div>
    <div class="form-group col-lg-12">
        <label for="color">图片类型：</label>
        <select id="selectImageType" class="form-control" name="type" onchange="checkImagetype()">
            @foreach($imageType as $item)
                <option value="{{ $item }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-12" style='display:none' id='Surl'>
        <label class='control-label'>供应商提供的URL：</label>
        <input class="form-control" id="url" placeholder="供应商提供的RUL" name='suppliers_url'/>
    </div>
    <div class="form-group col-lg-12" id='checktype'>
        <label for="brand_id">选择上传类型:</label>
        <input type="radio" name='uploadType' value='image' checked onClick="checktype()"/>上传图片
        <input type="radio" name='uploadType' value='zip' onClick="checktype()"/>上传压缩包
    </div>
    <div class="form-group col-lg-12" id='imagediv'>
        <label for="color">上传图片：</label>
        <input name='map0' type='file'/>
        <input name='map1' type='file'/>
        <input name='map2' type='file'/>
        <input name='map3' type='file'/>
        <input name='map4' type='file'/>
        <input name='map5' type='file'/>
    </div>
    <div class="form-group col-lg-12" style="display:none" id='zipdiv'>
        <label for="color">导入压缩包：<small>(仅ZIP格式的压缩包)</small></label>
        <input type="file" name='zip'/>
    </div>
    <script type="text/javascript">
        function checktype() {
            var uploadType = $("#checktype [name='uploadType']:checked").val();
            if (uploadType == 'image') {
                $('#zipdiv').hide();
                $('#imagediv').show();
            } else {
                $('#imagediv').hide();
                $('#zipdiv').show();
            }
        }
        function checkImagetype() {
            var imageType = $('select[name=type] option:selected').val();
            if (imageType == 'original') {
                $('#Surl').show();
            } else {
                $('#Surl').hide();
            }
        }
    </script>
@stop
 
 