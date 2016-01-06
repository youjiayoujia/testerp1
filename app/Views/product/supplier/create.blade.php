@extends('common.form')
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
@section('formAction') {{ route('productSupplier.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class="form-group col-lg-2">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="供货商名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for='province'>省份</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="province" onChange = "select()" class='form-control'></select>　
    </div>
    <div class="form-group col-lg-2">
        <label for='city'>城市</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="city" onChange = "select()" class='form-control'></select>
    </div>
    <div class="form-group col-lg-2">
        <label for="address">经纬度</label>
        <input type='text' class="form-control" id="address" placeholder="经纬度" name='address' value="{{ old('address') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="type">是否是线上供货商(否/是)</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='offline' {{ old('type') ? (old('type') == 'offline' ? 'checked' : '') : 'checked' }}>否
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='online' {{ old('type') ? (old('type') == 'online' ? 'checked' : '') : '' }}>是
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="url">供货商网址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="url" placeholder="供货商url" name='url' value="{{ old('url') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="telephone">供货商电话</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="telephone" placeholder="供货商电话" name='telephone' value="{{ old('telephone') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="purchase_id">采购员</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_id" placeholder="采购者id" name='purchase_id' value="{{ old('purchase_id') }}">
    </div>
    <div class="form-group col-lg-6">
        <label for="level">供货商评级</label>
        <select name='level' class='form-control'>
            <option value='1' {{ old('level') ? (old('level') == '1' ? 'selected' : '') : '' }}>1</option>
            <option value='2' {{ old('level') ? (old('level') == '2' ? 'selected' : '') : '' }}>2</option>
            <option value='3' {{ old('level') ? (old('level') == '3' ? 'selected' : '') : 'selected' }}>3</option>
            <option value='4' {{ old('level') ? (old('level') == '4' ? 'selected' : '') : '' }}>4</option>
            <option value='5' {{ old('level') ? (old('level') == '5' ? 'selected' : '') : '' }}>5</option>
        </select>
   </div>
   <div class='form-group col-lg-6'>
        <label name='created_by' class='control-group'>
            创建人
        </label>
        <input class='form-control' type='text' value='' name='created_by' id = 'created_by' readonly/>
   </div>
@stop
<script type='text/javascript'>
    window.onload = function() {
        init();
    };

</script>