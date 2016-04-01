@extends('common.form')
@section('formAction') {{ route('productUpdateImage') }} @stop
@section('formBody')
	<input type='hidden' value='{{$model->id}}' name="id" >
    <input type="hidden" name="user_id" value="1">
    <div class="form-group col-lg-12">
        <label class='control-label'>SPU ID</label>
        <input class="form-control" type="text" name='spu_id' value='{{$model->spu_id}}'/>
    </div>
    <div class="form-group col-lg-12">
        <label class='control-label'>产品ID</label>
        <input class="form-control" type="text" name='product_id' value='{{$model->id}}'/>
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
    <?php if(count($model->imageAll->toArray())>0){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">产品图片 :</div>
        <div class="panel-body">
            <?php foreach($model->imageAll as $image){ ?>
            <img src="{{ asset($image->path) }}/{{$image->name}}" width="300px" >
            <br>
            <?php } ?>
        </div>
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