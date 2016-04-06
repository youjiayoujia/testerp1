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
@stop
@section('formButton')
    <button type="submit" class="btn btn-success" name='edit' value='1'>保存</button>
    <button type="submit" class="btn btn-success" name='edit' value='2'>审核</button>
    <button type="reset" class="btn btn-default">取消</button>
@show{{-- 表单按钮 --}}
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
</script>