@extends('common.form')
@section('formAction') {{ route('orderBlacklist.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">收货人姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="收货人姓名" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="email" class="control-label">邮箱</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="zipcode" class="control-label">收货人邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="收货人邮编" name='zipcode' value="{{ old('zipcode') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="whitelist">纳入白名单</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="1" {{old('whitelist') ? (old('whitelist') == '1' ? 'checked' : '') : 'checked'}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="0" checked {{old('whitelist') ? (old('whitelist') == '0' ? 'checked' : '') : 'checked'}}>否
                </label>
            </div>
        </div>
    </div>
@stop