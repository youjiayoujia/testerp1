@extends('common.form')
@section('formAction') {{ route('logisticsZone.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="命名建议:shipping+数字(1区取1,2区取2,其他区取99)"
               name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_id">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control" id="logistics_id">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ $logisticses->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logisticses->logistics_type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="country_id" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="country_id" class="form-control" id="country_id">
            @foreach($country as $countries)
                <option value="{{ $countries->id }}" {{ $countries->id == old('$countries->country->id') ? 'selected' : '' }}>
                    {{ $countries->name }}
                </option>
            @endforeach
        </select>
    </div>
@stop