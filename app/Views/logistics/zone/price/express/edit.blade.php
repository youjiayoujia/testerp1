@extends('common.form')
@section('formAction') {{ route('logisticsZonePriceExpress.update', ['id' => $zonePriceExpress->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区报价</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="物流分区报价名称" name='name' value="{{ old('name') ? old('name') : $zonePriceExpress->name }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="shipping" class="control-label">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping" placeholder="种类" name="shipping" value="{{ old('shipping') ? old('shipping') : $zonePriceExpress->shipping }}" readonly>
    </div>
    <div class="form-group col-lg-4">
        <label for="fixed_weight" class="control-label">首重</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_weight" placeholder="首重" name='fixed_weight' value="{{ old('fixed_weight') ? old('fixed_weight') : $zonePriceExpress->fixed_weight }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="fixed_price" class="control-label">首重价格</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_price" placeholder="首重价格" name='fixed_price' value="{{ old('fixed_price') ? old('fixed_price') : $zonePriceExpress->fixed_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="continued_weight" class="control-label">续重</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_weight" placeholder="续重" name='continued_weight' value="{{ old('continued_weight') ? old('continued_weight') : $zonePriceExpress->continued_weight }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="continued_price" class="control-label">续重价格</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_price" placeholder="续重价格" name='continued_price' value="{{ old('continued_price') ? old('continued_price') : $zonePriceExpress->continued_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="other_fixed_price" class="control-label">其他固定费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_fixed_price" placeholder="其他固定费用" name='other_fixed_price' value="{{ old('other_fixed_price') ? old('other_fixed_price') : $zonePriceExpress->other_fixed_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="other_scale_price" class="control-label">其他比例费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_scale_price" placeholder="其他比例费用" name='other_scale_price' value="{{ old('other_scale_price') ? old('other_scale_price') : $zonePriceExpress->other_scale_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="discount" class="control-label">最后折扣</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="discount" placeholder="最后折扣" name='discount' value="{{ old('discount') ? old('discount') : $zonePriceExpress->discount }}">
    </div>
@stop