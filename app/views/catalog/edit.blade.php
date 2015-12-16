@extends('common.form')
@section('title') 编辑品类 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('catalog.index') }}">品类</a></li>
        <li class="active"><strong>编辑品类</strong></li>
    </ol>
@stop
@section('formTitle') 编辑品类 @stop
@section('formAction') {{ route('catalog.update', ['id' => $catalog->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name">名称</label>
        <input class="form-control" id="name" name='name' value="{{ old('name') ?  old('name') : $catalog->name }}">
    </div>
@stop