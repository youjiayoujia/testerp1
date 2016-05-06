@extends('common.form')
@section('formAction') {{ route('role.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="role_name" class='control-label'>角色</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role_name" placeholder="角色" name='role_name' value="{{ old('role_name') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="role" class='control-label'>role</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role" placeholder="role" name='role' value="{{ old('role') }}">
        </div>
    </div>
@stop