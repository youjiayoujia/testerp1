@extends('common.form')
@section('formAction') {{ route('productSupplier.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="供货商名字" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-1">
            <label for='province'>省份</label> 
            <select name="province" onChange = "select()" class='form-control'></select>
        </div>　
        <div class="form-group col-lg-1">
            <label for='city'>城市</label> 
            <select name="city" onChange = "select()" class='form-control'></select>
        </div>
        <div class="form-group col-lg-2">
            <label for="company">公司</label>
            <input type='text' class="form-control" id="company" placeholder="公司" name='company' value="{{ old('company') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="official_url">供货商官网</label>
            <input type='text' class="form-control" id="official_url" placeholder="供货商官网" name='official_url' value="{{ old('official_url') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="address">详细地址</label>
            <input type='text' class="form-control" id="address" placeholder="详细地址" name='address' value="{{ old('address') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="type">供货商类型</label>
            <div class='radio'>
                <label>
                    <input type='radio' name='type' value='1' {{ old('type') ? (old('type') == '1' ? 'checked' : '') : '' }}>线上
                </label>
                <label>
                    <input type='radio' name='type' value='0' {{ old('type') ? (old('type') == '0' ? 'checked' : '') : 'checked' }}>线下
                </label>
                <label>
                    <input type='radio' name='type' value='2' {{ old('type') ? (old('type') == '2' ? 'checked' : '') : '' }}>做货
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="url">供货商网址</label>
            <input type='text' class="form-control url" id="url" placeholder="供货商url" name='url' value="{{ old('url') }}" {{ old('type') ? old('type') != '1' ? 'readonly' : '' : 'readonly' }}>
        </div>
        <div class="form-group col-lg-3">
            <label for="contact_name">联系人</label>
            <input class="form-control" id="contact_name" placeholder="联系人" name='contact_name' value="{{ old('contact_name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="telephone">电话</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="telephone" placeholder="电话" name='telephone' value="{{ old('telephone') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="email">email</label>
            <input class="form-control" id="email" placeholder="email" name='email' value="{{ old('email') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="purchase_id">采购员</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_id" placeholder="采购者id" name='purchase_id' value="{{ old('purchase_id') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="level">供货商等级</label>
            <select name='level_id' class='form-control'>
            @foreach($levels as $level)
                <option value="{{$level->id}}" {{ old('level_id') ? (old('level_id') == $level->id ? 'selected' : '') : '' }}> {{$level->name}} </option>
            @endforeach
            </select>
       </div>
       <div class='form-group col-lg-4'>
            <label name='created_by' class='control-group'>
                创建人
            </label>
            <input class='form-control' type='text' value='1' name='created_by' id = 'created_by' readonly/>
       </div>
   </div>
@stop
@section('pageJs')
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        var buf = new Array();
        buf[0] = "{{ old('province') }}" ;
        buf[1] = "{{ old('city') }}" ;
        init(buf[0],buf[1]);

        $('.radio').click(function(){
            if($(this).find(':radio:checked').val() != '1') {
                $(this).parent().parent().next().find('.url').val('');
                $(this).parent().parent().next().find('.url').attr('readonly', true);
            }
            else {
                $(this).parent().parent().next().find('.url').attr('readonly', false);
            }
        });
    });
</script>
@stop