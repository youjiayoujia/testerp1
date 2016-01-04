@extends('common.form')
@section('title') 编辑渠道 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('channel.index') }}">渠道</a></li>
        <li class="active"><strong>编辑渠道</strong></li>
    </ol>
@stop
@section('formTitle') 编辑渠道 @stop
@section('formAction') {{ route('channel.update', ['id' => $channel->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-6">
        <label for="name" class='control-label'>渠道名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') ? old('name') : $channel->name }}">
    </div>
    <div class="form-group col-lg-6">
        <label for="alias" class='control-label'>渠道名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="alias" placeholder="渠道中文名称" name='alias' value="{{ old('alias') ? old('alias') : $channel->alias }}">
    </div>
@stop