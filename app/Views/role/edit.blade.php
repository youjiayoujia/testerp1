@extends('common.form')
@section('formAction') {{ route('role.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="role" class='control-label'>角色</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role" placeholder="角色" name='role' value="{{ $model->role }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="role_name" class='control-label'>role_name</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role_name" placeholder="role_name" name='role_name' value="{{ $model->role_name }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="permission_name" class='control-label'>选择角色对应的权限</label>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            @foreach($permissions as $permission)
                <label class="checkbox-inline">
                    <input type="checkbox" id="permission" value="{{$permission->id}}" name="role_permission[]" {{ in_array($permission->id, $select_permission)? 'checked' : '' }}> {{$permission->action_name}}
                </label>
            @endforeach
        </div>
    </div>
@stop