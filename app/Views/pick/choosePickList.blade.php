@extends('common.form')
@section('formAction') {{ route('pickList.processBase') }} @stop
@section('formBody')
    <div class='row'>
        <input type='hidden' name='flag' value="{{ $content }}">
        <div class='form-group col-lg-2'>
            <label>拣货单号:</label>
            <input type='text' name='picknum' class='form-control' placeholder="picknum">
        </div>
    </div>
@stop