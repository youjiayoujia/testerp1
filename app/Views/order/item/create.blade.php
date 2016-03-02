@extends('common.form')
@section('formAction') {{ route('orderItem.store') }} @stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="order_item_id" class='control-label'>产品ID</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="order_item_id" placeholder="产品ID" name='order_item_id' value="{{ old('order_item_id') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="order_id" class='control-label'>订单ID</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="order_id" placeholder="订单ID" name='order_id' value="{{ old('order_id') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="sku" class='control-label'>sku</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="qty" class='control-label'>数量</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="qty" placeholder="数量" name='qty' value="{{ old('qty') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="price" class='control-label'>金额</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="金额" name='price' value="{{ old('price') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="status" class='control-label'>订单状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="status" placeholder="订单状态" name='status' value="{{ old('status') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="ship_status" class='control-label'>发货状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="ship_status" placeholder="发货状态" name='ship_status' value="{{ old('ship_status') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="is_gift" class='control-label'>是否赠品</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="is_gift" placeholder="是否赠品" name='is_gift' value="{{ old('is_gift') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="remark" class='control-label'>备注</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
    </div>
@stop