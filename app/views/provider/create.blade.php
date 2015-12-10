@extends('common.form')
@section('title') 添加供货商 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">供货商</a></li>
        <li class="active"><strong>添加供货商</strong></li>
    </ol>
@stop
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    window.onload = init;
</script>
@section('formTitle') 添加供货商 @stop
@section('formAction') {{ route('provider.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="供货商名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="detail_address" class='control-label'>详细地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <p for='province'>省份</p> <select name="province" onChange = "select()" class='form-control'></select>　
        <p for='city'>城市</p> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
    <div class="form-group">
        <label for="address">供货商地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="address" placeholder="供货商地址" name='address' value="{{ old('address') }}">
    </div>
    <div class="form-group">
        <label for="online">是否是线上供货商(否/是)</label>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='0' checked>否
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='1'>是
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="url">供货商网址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="url" placeholder="供货商url" name='url' value="{{ old('url') }}">
    </div>
    <div class="form-group">
        <label for="telephone">供货商电话</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="telephone" placeholder="供货商电话" name='telephone' value="{{ old('telephone') }}">
    </div>
    <div class="form-group">
        <label for="purchaseid">采购员</label>
        <input class="form-control" id="purchaseid" placeholder="采购者id" name='purchaseid' value="{{ old('purchaseid') }}">
    </div>
    <div class="form-group">
        <label for="level">供货商评级</label>
        <select name='level' class='form-control'>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        </select>
   </div>
   <div class='form-group'>
        <label name='created_by' class='control-group'>
            创建人
        </label>
        <input class='form-control' type='text' value='' name='created_by' id = 'created_by' readonly/>
   </div>
@stop