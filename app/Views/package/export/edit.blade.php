@extends('common.form')
@section('formAction') {{ route('exportPackage.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>模板名</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="模板名" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">字段列表</div>
        <div class="panel-body">
            @foreach($fields as $key => $field)
            <div class="row">
                <div class='form-grou col-lg-2'>
                    <input type='checkbox' name='fieldNames[]' value="{{ $key }}" {{ $model->inFields($key) ? 'checked' : ''}}>{{ $field }}
                </div>
                <div class='form-group col-lg-2'>
                    <input type='text' class="form-control col-lg-2" name='{{$key}},level' value="{{ old('$key'+',level') ? old('$key'+',level') : $model->inFields($key)}}">
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop