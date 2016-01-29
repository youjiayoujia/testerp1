@extends('common.form')
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
@section('formAction') {{ route('warehouse.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
   <input type="hidden" name="_method" value="PUT"/>
   <div class='row'>
   <div class="form-group col-lg-3">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="仓库名字" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
    </div>
    <div class="form-group col-lg-3">
        <label for='province'>省份</label> <select name="province" onChange = "select()" class='form-control'></select>　
    </div>
    <div class='form-group col-sm-3'> 
        <label for='city'>城市</label> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
    <div class="form-group col-lg-3">
        <label for="type">仓库类型</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='本地仓库' {{old('type') ? (old('type') == '本地仓库' ? 'checked' : '') : ($model->type  == '本地仓库' ? 'checked' : '')}} >本地仓库
            </label>   
            <label>
                <input type='radio' name='type' value='海外仓库' {{old('type') ? (old('type') == '海外仓库' ? 'checked' : '') : ($model->type  == '海外仓库' ? 'checked' : '')}}>海外仓库
            </label>
            <label>
                <input type='radio' name='type' value='第三方仓库' {{old('type') ? (old('type') == '第三方仓库' ? 'checked' : '') : ($model->type  == '第三方仓库' ? 'checked' : '')}}>第三方仓库
            </label>
        </div>
    </div>
    </div>
    <div class='row'>
    <div class="form-group col-lg-4">
        <label for="volumn">仓库体积(m3)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="volumn" placeholder="仓库体积" name='volumn' value="{{ old('volumn') ?  old('volumn') : $model->volumn }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="is_available">仓库是否启用</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{old('is_available') ? (old('is_available') == 'Y' ? 'checked' : '') : ($model->is_available  == 'Y' ? 'checked' : '')}}>启用
            </label>   
            <label>
                <input type='radio' name='is_available' value='N' {{old('is_available') ? (old('is_available') == 'N' ? 'checked' : '') : ($model->is_available  == 'N' ? 'checked' : '')}}>不启用
            </label>
        </div>    
    </div>
    <div class="form-group col-lg-4">
        <label for="is_default">是否是默认仓库</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_default' value='Y' {{old('is_default') ? (old('is_default') == 'Y' ? 'checked' : '') : ($model->is_default  == 'Y' ? 'checked' : '')}}>默认仓库
            </label>   
            <label>
                <input type='radio' name='is_default' value='N' {{old('is_default') ? (old('is_default') == 'N' ? 'checked' : '') : ($model->is_default  == 'N' ? 'checked' : '')}}>非默认仓库
            </label>
        </div>    
    </div> 
    </div>
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf[0] = "{{ old('province') ? old('province') : $model->province }}" ;
        buf[1] = "{{ old('city') ? old('city') : $model->city }}" ;
        init(buf[0],buf[1]);
    };
</script>