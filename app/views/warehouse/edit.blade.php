@extends('common.form')
@section('title') 编辑供货商 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehouse.index') }}">供货商</a></li>
        <li class="active"><strong>编辑供货商</strong></li>
    </ol>
@stop
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>

@section('formTitle') 编辑供货商 @stop
@section('formAction') {{ route('warehouse.update', ['id' => $warehouse->id]) }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
   <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="仓库名字" name='name' value="{{ old('name') ?  old('name') : $warehouse->name }}">
    </div>
    <div class="form-group">
        <label for="detail_address" class='control-label'>详细地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <p for='province'>省份</p> <select name="province" onChange = "select()" class='form-control'></select>　
        <p for='city'>城市</p> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
    <div class="form-group">
        <label for="type">仓库类型</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='本地仓库' {{ $warehouse->type == '本地仓库' ? 'checked' : '' }} >本地仓库
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='海外仓库' {{ $warehouse->type == '海外仓库' ? 'checked' : '' }}>海外仓库
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='第三方仓库' {{ $warehouse->type == '第三方仓库' ? 'checked' : '' }}>第三方仓库
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="volumn">仓库体积</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="volumn" placeholder="仓库体积" name='volumn' value="{{ old('volumn') ?  old('volumn') : $warehouse->volumn }}">
    </div>
    <div class="form-group">
        <label for="is_available">仓库是否启用</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{ $warehouse->is_available == 'Y' ? 'checked' : '' }}>启用
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='N' {{ $warehouse->is_available == 'N' ? 'checked' : '' }}>不启用
            </label>
        </div>    
    </div>
    <div class="form-group">
        <label for="is_default">是否是默认仓库</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_default' value='Y' {{ $warehouse->is_default == 'Y' ? 'checked' : '' }}>默认仓库
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_default' value='N' {{ $warehouse->is_default == 'N' ? 'checked' : '' }}>非默认仓库
            </label>
        </div>    
    </div> 
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf = "{{ $warehouse->detail_address }}".split(' ');
        init(buf[0],buf[1]);
    };
</script>