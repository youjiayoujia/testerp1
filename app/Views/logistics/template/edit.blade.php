@extends('common.form')
@section('formAction') {{ route('logisticsTemplate.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">面单名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="面单名称" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="view" class="control-label">视图</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="view" placeholder="视图" name='view' value="{{ old('view') ?  old('view') : $model->view }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="size" class="control-label">尺寸</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="size" placeholder="尺寸" name='size' value="{{ old('size') ?  old('size') : $model->size }}">
        </div>
    </div>
@stop