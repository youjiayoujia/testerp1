@extends('common.form')
@section('title') 添加物流 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active"><strong>添加物流</strong></li>
    </ol>
@stop
@section('formTitle') 添加物流 @stop
@section('formAction') {{ route('logistics.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="logistics_name">物流</label>
        <input class="form-control" id="logistics_name" placeholder="物流" name='logistics_name' value="{{ old('logistics_name') }}">
    </div>
    <div class="form-group">
        <label for="country">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('country') }}">
    </div>
@stop