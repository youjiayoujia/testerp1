@extends('common.form')
@section('title') 添加渠道 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('channel.index') }}">渠道</a></li>
        <li class="active"><strong>添加渠道</strong></li>
    </ol>
@stop
@section('formTitle') 添加渠道 @stop
@section('formAction') {{ route('channel.store') }} @stop
@section('formBody')
    <div class="form-group col-lg-12">
        <label for="name" class='control-label'>渠道名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-12">
        <label for="alias" class='control-label'>渠道别称</label>
        <input type='text' class="form-control" id="alias" placeholder="渠道中文名称" name='alias' value="{{ old('alias') }}">
    </div>
@stop