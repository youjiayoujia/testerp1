@extends('common.form')
@section('formAction') {{ route('user.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="用户姓名" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="email" class='control-label'>邮箱（用于登录）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="email" placeholder="用户邮箱" name='email' value="{{ old('email') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="password" class='control-label'>密码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='password' class="form-control" id="password" placeholder="用户密码" name='password' value="{{ old('password') }}">
        </div>
    </div>
@stop