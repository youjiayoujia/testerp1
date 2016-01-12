@extends('common.form')
@section('formAction') {{ route('logisticsZonePricePacket.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区报价</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="物流分区报价名称" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="species_id">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="species_id" class="form-control" id="species_id">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ $logisticses->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logisticses->species }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="price" class="control-label">价格</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="价格" name='price' value="{{ old('price') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="other_price" class="control-label">其他费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_price" placeholder="其他费用" name='other_price' value="{{ old('other_price') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="discount" class="control-label">最后折扣</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="discount" placeholder="最后折扣" name='discount' value="{{ old('discount') }}">
    </div>
@stop