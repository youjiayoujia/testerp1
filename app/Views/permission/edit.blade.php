@extends('common.form')
@section('formAction') {{ route('permission.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="action_name" class='control-label'>权限</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="action_name" placeholder="权限" name='action_name' value="{{ $model->action_name }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="action" class='control-label'>action</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="action" placeholder="action" name='action' value="{{ $model->action }}">
        </div>
    </div>
@stop