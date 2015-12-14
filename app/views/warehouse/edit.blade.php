@extends('common.form')
@section('title') 编辑仓库 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehouse.index') }}">仓库</a></li>
        <li class="active"><strong>编辑仓库</strong></li>
    </ol>
@stop
@section('formTitle') 编辑仓库 @stop
@section('formAction') {{ route('warehouse.update', ['id' => $warehouse->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="size">仓库</label>
        <input class="form-control" id="warehouse_name" placeholder="仓库" name='warehouse_name' value="{{ old('name') ?  old('name') : $warehouse->name }}">
    </div>
    <div class="form-group">
        <label for="size">国家</label>
        <input class="form-control" id="country" placeholder="国家" name='country' value="{{ old('country') ?  old('country') : $warehouse->country }}">
    </div>
@stop