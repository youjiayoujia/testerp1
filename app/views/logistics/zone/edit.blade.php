@extends('common.form')
@section('formAction') {{ route('logisticsZone.update', ['id' => $zone->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="命名建议:shipping+数字(1区取1,2区取2,其他区取99)"
               name='name' value="{{ old('name') ? old('name') : $zone->name }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_id">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control">
            @foreach($logistics as $logisticses)
                <option value="{{$logisticses->id}}" {{$logisticses->id == $zone->logistics_id ? 'selected' : ''}}>
                    {{$logisticses->logistics_type}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="countries" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="countries" placeholder="国家" name='countries' value="{{ old('countries') ? old('countries') : $zone->countries }}">
    </div>
@stop