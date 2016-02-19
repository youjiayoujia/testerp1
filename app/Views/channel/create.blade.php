@extends('common.form')
@section('formAction') {{ route('channel.store') }} @stop
@section('formBody')
    <div class="form-group col-lg-6">
        <label for="name" class='control-label'>渠道名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-12">
        <label for="brief" class='control-label'>描述</label>
        <textarea class="form-control" rows="3" name="brief">{{ old('brief') }}</textarea>
    </div>
@stop