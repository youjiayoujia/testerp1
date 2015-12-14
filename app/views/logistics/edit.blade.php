@extends('common.form')
@section('title') 编辑物流 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active"><strong>编辑物流</strong></li>
    </ol>
@stop
@section('formTitle') 编辑物流 @stop
@section('formAction') {{ route('logistics.update', ['id' => $logistics->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="size">物流</label>
        <input class="form-control" id="logistics_name" placeholder="物流" name='logistics_name' value="{{ old('name') ?  old('name') : $logistics->name }}">
    </div>
    <div class="form-group">
        <label for="size">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('country') ?  old('country') : $logistics->country }}">
    </div>
@stop