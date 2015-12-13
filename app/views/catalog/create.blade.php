@extends('common.form')
@section('title') 添加品类 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('catalog.index') }}">品类</a></li>
        <li class="active"><strong>添加品类</strong></li>
    </ol>
@stop
@section('formTitle') 添加品类 @stop
@section('formAction') {{ route('catalog.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="name">名称</label>
        <input class="form-control" id="name" placeholder="名称" name='name' value="帽子{{ date('Y-m-d H:i:s') }}">
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">属性</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <input class="form-control" placeholder="属性名称" name='setName' value="颜色{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" placeholder="属性值" name='setValues[]' value="红{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" placeholder="属性值" name='setValues[]' value="黄{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" placeholder="属性值" name='setValues[]' value="蓝{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" placeholder="属性值" name='setValues[]' value="绿{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop