@extends('common.form')
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
@section('formAction') {{ route('productSupplier.update', ['id' => $supplier->id]) }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
    <div class="form-group col-lg-2">
        <label for="name">供货商名</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="供货商名" name='name' value="{{ old('name') ?  old('name') : $supplier->name }}">
    </div>
    <div class="form-group col-lg-2">
        <label for='province'>省份</label> 
        <select name="province" onChange = "select()" class='form-control'></select>　
    </div>
    <div class='form-group col-lg-2'> 
        <label for='city'>城市</label> 
        <select name="city" onChange = "select()" class='form-control'></select>
    </div>
     <div class="form-group col-lg-2">
        <label for="address">经纬度</label>
        <input type='text' class="form-control" id="address" placeholder="经纬度" name='address' value="{{ old('address') ?  old('address') : $supplier->address }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="type">是否是线上供货商(否/是)</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='offline' {{old('type') ? (old('type') == 'offline' ? 'checked' : '') : ($supplier->type  == 'offline' ? 'checked' : '')}}>否
            </label>
            <label>
                <input type='radio' name='type' value='online' {{old('type') ? (old('type') == 'online' ? 'checked' : '') : ($supplier->type  == 'online' ? 'checked' : '')}}>是
            </label>
        </div>
    </div>
       </div>
      <div class="row"> 
     <div class="form-group col-lg-4">
        <label for="url">线上供货商网址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="url" placeholder="供货商url" name='url' value="{{ old('url') ?  old('url') : $supplier->url }}">
    </div> 
    <div class="form-group col-lg-4">
        <label for="telephone">电话</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="telephone" placeholder="供货商电话" name='telephone' value="{{ old('telephone') ?  old('telephone') : $supplier->telephone }}">
    </div> 
     <div class="form-group col-lg-4">
        <label for="purchase_id">采购员</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="purchase_id" placeholder="采购员id" name='purchase_id' value="{{ old('purchase_id') ?  old('purchase_id') : $supplier->purchase_id }}">
    </div> 
    </div>
    <div class="row">
    <div class="form-group col-lg-6">
        <label for="level">评级</label>
        <select id='level' name='level' class='form-control' >
        <option value='1' {{ $supplier->level == '1' ? 'selected' : ''}}>1</option>
        <option value='2' {{ $supplier->level == '2' ? 'selected' : ''}}>2</option>
        <option value='3' {{ $supplier->level == '3' ? 'selected' : ''}}>3</option>
        <option value='4' {{ $supplier->level == '4' ? 'selected' : ''}}>4</option>
        <option value='5' {{ $supplier->level == '5' ? 'selected' : ''}}>5</option>
        </select>
   </div>
   <div class='form-group col-lg-6'>
        <label name='created_by' class='control-group'>
            创建人
        </label>
        <input class='form-control' type='text' value='' name='created_by' id = 'created_by' readonly/>
   </div>
   </div>
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf[0] = "{{ old('province') ? old('province') : $supplier->province }}" ;
        buf[1] = "{{ old('city') ? old('city') : $supplier->city }}" ;
        init(buf[0],buf[1]);
    };
</script>