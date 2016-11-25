@extends('common.form')
@section('formAction') {{ route('countriesChange.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="country_from" class='control-label'>来源国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="来源国家" name='country_from' value="{{ old('country_from') ? old('country_from') : $model->country_from }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="country_to" class='control-label'>目标国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="目标国家" name='country_to' value="{{ old('country_to') ? old('country_to') : $model->country_to }}">
        </div>
    </div>
@stop