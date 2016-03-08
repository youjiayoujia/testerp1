@extends('common.form')
@section('formAction') {{ route('channel.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>渠道名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-12">
            <label for="brief" class='control-label'>描述</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <textarea class="form-control" rows="3" name="brief">{{ old('brief') ? old('brief') : $model->brief }}</textarea>
        </div>
    </div>
@stop