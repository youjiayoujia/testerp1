@extends('common.form')
@section('formAction') {{ route('countries.update', ['id' => $model->id]) }} @stop
@section('formBody')
   <input type="hidden" name="_method" value="PUT"/>
   <div class='row'>
        <div class="form-group col-lg-6">
            <label for="code" class='control-label'>国家名</label>
            <input type='text' class="form-control" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="code" class='control-label'>简称</label>
            <input type='text' class="form-control" name='code' value="{{ old('code') ? old('code') : $model->code }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-6'> 
            <label for='identify'>中文名</label> 
            <input type='text' class="form-control" name="cn_name" value="{{ old('cn_name') ? old('cn_name') : $model->cn_name }}">
        </div>
        <div class='form-group col-lg-6'> 
            <label for='rate'>number</label> 
            <input type='text' class="form-control" name="number" value="{{ old('number') ? old('number') : $model->number }}">
        </div>
    </div>
@stop