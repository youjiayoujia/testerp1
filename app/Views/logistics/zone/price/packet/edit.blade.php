@extends('common.form')
@section('formAction') {{ route('logisticsZonePricePacket.update', ['id' => $zonePricePacket->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区报价</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="物流分区报价名称" name='name' value="{{ old('name') ? old('name') : $zonePricePacket->name }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="shipping" class="control-label">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping" placeholder="种类" name="shipping" value="{{ old('shipping') ? old('shipping') : $zonePricePacket->shipping }}" readonly>
    </div>
    <div class="form-group col-lg-4">
        <label for="price" class="control-label">价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="价格(/kg)" name='price' value="{{ old('price') ? old('price') : $zonePricePacket->price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="other_price" class="control-label">其他费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_price" placeholder="其他费用" name='other_price' value="{{ old('other_price') ? old('other_price') : $zonePricePacket->other_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="discount" class="control-label">最后折扣</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="discount" placeholder="最后折扣(八折录入0.8)" name='discount' value="{{ old('discount') ? old('discount') : $zonePricePacket->discount }}">
    </div>
@stop