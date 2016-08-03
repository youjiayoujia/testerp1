@extends('common.form')
@section('formAction') {{ route('logisticsEmailTemplate.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="customer" class="control-label">协议客户</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer" placeholder="协议客户" name='customer' value="{{ old('customer') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="zipcode" class="control-label">邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="邮编" name='zipcode' value="{{ old('zipcode') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="address" class="control-label">发件地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="address" placeholder="发件地址" name='address' value="{{ old('address') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="phone" class="control-label">电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="phone" placeholder="电话" name='phone' value="{{ old('phone') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="sender" class="control-label">寄件人</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="sender" placeholder="寄件人" name='sender' value="{{ old('sender') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="unit" class="control-label">退件单位</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="unit" placeholder="退件单位" name='unit' value="{{ old('unit') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="country_code" class="control-label">国家代码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="country_code" placeholder="国家代码" name='country_code' value="{{ old('country_code') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="province" class="control-label">省份</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="province" placeholder="省份" name='province' value="{{ old('province') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="city" class="control-label">城市</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="city" placeholder="城市" name='city' value="{{ old('city') }}">
        </div>
        <div class="form-group col-lg-12">
            <label for="remark" class="control-label">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
        </div>
    </div>
@stop