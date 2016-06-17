@extends('common.form')
@section('formAction') {{ route('logisticsEmailTemplate.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="customer" class="control-label">协议客户</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer" placeholder="协议客户" name='customer' value="{{ old('customer') ? old('customer') : $model->customer }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="zipcode" class="control-label">邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="邮编" name='zipcode' value="{{ old('zipcode') ? old('zipcode') : $model->zipcode }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="address" class="control-label">发件地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="address" placeholder="发件地址" name='address' value="{{ old('address') ? old('address') : $model->address }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="phone" class="control-label">电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="phone" placeholder="电话" name='phone' value="{{ old('phone') ? old('phone') : $model->phone }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="sender" class="control-label">寄件人</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="sender" placeholder="寄件人" name='sender' value="{{ old('sender') ? old('sender') : $model->sender }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="unit" class="control-label">退件单位</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="unit" placeholder="退件单位" name='unit' value="{{ old('unit') ? old('unit') : $model->unit }}">
        </div>
        <div class="form-group col-lg-12">
            <label for="remark" class="control-label">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
        </div>
    </div>
@stop