@extends('common.form')
@section('formAction') {{ route('productUpdateImage') }} @stop
@section('formBody')
	<input type='hidden' value='{{$model->id}}' name="id" >
    <input type="hidden" name="user_id" value="1">
    <div class="form-group col-lg-12">
        <!--<label class='control-label'>SPU ID</label>-->
        <input class="form-control" type="hidden" name='spu_id' value='{{$model->spu_id}}'/>
    </div>
    <div class="form-group col-lg-12">
        <!--<label class='control-label'>产品ID</label>-->
        <input class="form-control" type="hidden" name='product_id' value='{{$model->id}}'/>
    </div>
    <div class="form-group col-lg-12">
        <label for="color">图片类型：</label>
        <select class="form-control" name="type">
            @foreach(config('product.image.types') as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-12" id='checkType'>
        <label for="brand_id">选择上传类型:</label>
        <input type="radio" name='uploadType' value='image' checked onClick="checkType()"/>上传图片
        <input type="radio" name='uploadType' value='zip' onClick="checkType()"/>上传压缩包
    </div>
    <div class="form-group col-lg-12" id='imageDiv'>
        <label for="color">上传图片：</label>
        <input name='image0' type='file'/>
        <input name='image1' type='file'/>
        <input name='image2' type='file'/>
        <input name='image3' type='file'/>
        <input name='image4' type='file'/>
        <input name='image5' type='file'/>
    </div>
    <div class="form-group col-lg-12" style="display:none" id='zipDiv'>
        <label for="color">导入压缩包：
            <small>(仅ZIP格式的压缩包)</small>
        </label>
        <input type="file" name='zip'/>
    </div>
    <br>
    <?php if(count($model->imageAll->where("type",'original')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">original :</div>
        <?php foreach($model->imageAll->where("type",'original') as $image){ ?>
            <div class="panel-body">
                <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
                <div class='upimage' style="float:right"><input name='original_image_<?php echo $image->id ?>' type='file'/></div>
                <br>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if(count($model->imageAll->where("type",'amazon')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">Amazon :</div>
        <?php foreach($model->imageAll->where("type",'amazon') as $image){ ?>
            <div class="panel-body">
                <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
                <div class='upimage' style="float:right"><input name='amazon_image_<?php echo $image->id ?>' type='file'/></div>
                <br>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if(count($model->imageAll->where("type",'ebay')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">Ebay :</div>
        <?php foreach($model->imageAll->where("type",'ebay') as $image){ ?>
            <div class="panel-body">   
                <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
                <div class='upimage' style="float:right"><input name='ebay_image_<?php echo $image->id ?>' type='file'/></div>
                <br>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if(count($model->imageAll->where("type",'aliexpress')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">aliexpress :</div>
        <?php foreach($model->imageAll->where("type",'aliexpress') as $image){ ?>
        <div class="panel-body">   
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
            <div class='upimage' style="float:right"><input name='aliexpress_image_<?php echo $image->id ?>' type='file'/></div>
            <br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if(count($model->imageAll->where("type",'public')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">public :</div>
        <?php foreach($model->imageAll->where("type",'public') as $image){ ?>
        <div class="panel-body">   
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
            <div class='upimage' style="float:right"><input name='public_image_<?php echo $image->id ?>' type='file'/></div>
            <br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if(count($model->imageAll->where("type",'choies')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">choies :</div>
        <?php foreach($model->imageAll->where("type",'choies') as $image){ ?>
        <div class="panel-body">   
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
            <div class='upimage' style="float:right"><input name='choies_image_<?php echo $image->id ?>' type='file'/></div>
            <br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if(count($model->imageAll->where("type",'wish')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">wish :</div>
        <?php foreach($model->imageAll->where("type",'wish') as $image){ ?>
        <div class="panel-body">   
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
            <div class='upimage' style="float:right"><input name='wish_image_<?php echo $image->id ?>' type='file'/></div>
            <br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if(count($model->imageAll->where("type",'lazada')->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">lazada :</div>
        <?php foreach($model->imageAll->where("type",'lazada') as $image){ ?>
        <div class="panel-body">   
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px" >
            <div class='upimage' style="float:right"><input name='lazada_image_<?php echo $image->id ?>' type='file'/></div>
            <br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               请填写图片不编辑原因
            </h4>
         </div>
         <input type="text" class="modal-body" name="image_edit_not_pass_remark" style="margin:10px 0px 10px 50px;width:500px;" value="{{ old('image_edit_not_pass_remark') ?  old('image_edit_not_pass_remark') : $model->image_edit_not_pass_remark }}"/>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="submit" class="btn btn-primary" name='edit_status' value='image_unedited'>
               提交
            </button>
         </div>
      </div>
</div>
</div>

    <div class="container kv-main">
            <div class="page-header">
            
            </div>
            
            <form enctype="multipart/form-data">
                <div class="form-group">
                    <input id="file-5" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="http://www.youjia1.com/product/updateImage" data-preview-file-icon="">
                </div>
            </form>
    </div>
        
@stop
@section('formButton')
    <button type="submit" class="btn btn-success" name='edit_status' value='image_edited'>保存</button>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">图片不编辑</button>
    <button type="reset" class="btn btn-default">取消</button>
    
@show{{-- 表单按钮 --}}
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">{{-- BOOTSTRAP CSS --}} 
@section('pageJs')

<script type="text/javascript" src="{{ asset('js/fileinput.js') }}"></script>
<!-- 引用核心层插件 -->
<script type="text/javascript" src="{{ asset('js/fileinput_locale_zh.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
<script src="http://libs.useso.com/js/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
    function checkType() {
        var uploadType = $("#checkType [name='uploadType']:checked").val();
        if (uploadType == 'image') {
            $('#zipDiv').hide();
            $('#imageDiv').show();
        } else {
            $('#imageDiv').hide();
            $('#zipDiv').show();
        }
    }

    $("#file-0").fileinput({
            'allowedFileExtensions' : ['jpg', 'png','gif'],
        });
        $("#file-1").fileinput({
            uploadUrl: '#', // you must set a valid URL here else you will get an error
            allowedFileExtensions : ['jpg', 'png','gif'],
            overwriteInitial: false,
            maxFileSize: 1000,
            maxFilesNum: 10,
            //allowedFileTypes: ['image', 'video', 'flash'],
            slugCallback: function(filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
        });
        /*
        $(".file").on('fileselect', function(event, n, l) {
            alert('File Selected. Name: ' + l + ', Num: ' + n);
        });
        */
        $("#file-3").fileinput({
            showUpload: false,
            showCaption: false,
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
        });
        $("#file-4").fileinput({
            uploadExtraData: {kvId: '10'}
        });
        $(".btn-warning").on('click', function() {
            if ($('#file-4').attr('disabled')) {
                $('#file-4').fileinput('enable');
            } else {
                $('#file-4').fileinput('disable');
            }
        });    
        $(".btn-info").on('click', function() {
            $('#file-4').fileinput('refresh', {previewClass:'bg-info'});
        });
        /*
        $('#file-4').on('fileselectnone', function() {
            alert('Huh! You selected no files.');
        });
        $('#file-4').on('filebrowse', function() {
            alert('File browse clicked for #file-4');
        });
        */
        $(document).ready(function() {
            $("#test-upload").fileinput({
                'showPreview' : false,
                'allowedFileExtensions' : ['jpg', 'png','gif'],
                'elErrorContainer': '#errorBlock'
            });
            /*
            $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
                alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
            });
            */
        });
</script>

@stop
