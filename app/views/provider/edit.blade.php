@extends('common.form')
@section('title') 编辑供货商 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('provider.index') }}">供货商</a></li>
        <li class="active"><strong>编辑供货商</strong></li>
    </ol>
@stop
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    window.onload = function(){
        init();
    };
</script>
@section('formTitle') 编辑供货商 @stop
@section('formAction') {{ route('provider.update', ['id' => $product->id]) }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name">供货商名</label>
        <input type='text' class="form-control" id="name" placeholder="供货商名" name='name' value="{{ old('name') ?  old('name') : $product->name }}">
    </div>
        <div class="form-group">
        <label for="detail_address" class='control-label'>详细地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <p for='province'>省份</p> <select name="province" onChange = "select()" class='form-control'>
            <option value="{{ old('province') ?  old('province') : $product->province }}" checked>{{ $product->province }}</option>
        </select>　
        <p for='city'>城市</p> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
     <div class="form-group">
        <label for="address">地址</label>
        <input type='text' class="form-control" id="address" placeholder="供货商地址" name='address' value="{{ old('address') ?  old('address') : $product->address }}">
    </div>
    <div class="form-group">
        <label for="online">是否是线上供货商(否/是)</label>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='0' checked>0
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='1'>1
            </label>
        </div>
    </div>
     <div class="form-group">
        <label for="url">线上供货商网址</label>
        <input type='text' class="form-control" id="url" placeholder="供货商url" name='url' value="{{ old('url') ?  old('url') : $product->url }}">
    </div> 
    <div class="form-group">
        <label for="telephone">电话</label>
        <input type='text' class="form-control" id="telephone" placeholder="供货商电话" name='telephone' value="{{ old('telephone') ?  old('telephone') : $product->telephone }}">
    </div> 
     <div class="form-group">
        <label for="purchaseid">采购员</label>
        <input type='text' class="form-control" id="purchaseid" placeholder="采购员id" name='purchaseid' value="{{ old('purchaseid') ?  old('purchaseid') : $product->purchase_id }}">
    </div> 
    <div class="form-group">
        <label for="level">]评级</label>
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