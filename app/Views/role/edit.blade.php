@extends('common.form')
@section('formAction') {{ route('role.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="role_name" class='control-label'>角色</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role_name" placeholder="角色" name='role_name' value="{{ $model->role_name }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="role" class='control-label'>role</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role" placeholder="role" name='role' value="{{ $model->role }}">
        </div>
    </div>
@stop