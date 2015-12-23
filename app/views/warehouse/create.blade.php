@extends('common.form')
@section('title') 添加仓库 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('Warehouse.index') }}">仓库</a></li>
        <li class="active"><strong>添加仓库</strong></li>
    </ol>
@stop
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    window.onload = init;
</script>
@section('formTitle') 添加仓库 @stop
@section('formAction') {{ route('Warehouse.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="仓库名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="detail_address" class='control-label'>(省/市)地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='row'>
            <div class='col-sm-6'>
                <label for='province'>省份</label> <select name="province" onChange = "select()" class='form-control'></select>　
            </div>
            <div class='col-sm-6'> 
                <label for='city'>城市</label> <select name="city" onChange = "select()" class='form-control'></select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="type">仓库类型</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='本地仓库' {{ old('type') ? (old('type') == '本地仓库' ? 'checked' : '') : 'checked' }}>本地仓库
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='海外仓库' {{ old('type') ? (old('type') == '海外仓库' ? 'checked' : '') : '' }}>海外仓库
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='第三方仓库' {{ old('type') ? (old('type') == '第三方仓库' ? 'checked' : '') : '' }}>第三方仓库
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="volumn">仓库体积(m3)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="volumn" placeholder="仓库体积" name='volumn' value="{{ old('volumn') }}">
    </div>
    <div class="form-group">
        <label for="is_available">仓库是否启用</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{ old('is_available') ? (old('is_available') == 'Y' ? 'checked' : '') : '' }}>启用
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='N' {{ old('is_available') ? (old('is_available') == 'N' ? 'checked' : '') : 'checked' }}>不启用
            </label>
        </div>    
    </div>
    <div class="form-group">
        <label for="is_default">是否是默认仓库</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_default' value='Y' {{ old('is_default') ? (old('is_default') == 'Y' ? 'checked' : '') : '' }}>默认仓库
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_default' value='N' {{ old('is_default') ? (old('is_default') == 'N' ? 'checked' : '') : 'checked' }}>非默认仓库
            </label>
        </div>    
    </div>
@stop