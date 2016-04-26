@extends('common.form')
@section('formAction') {{ route('package.excelProcess') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-4">
    <label>excel导入文件</label>
        <input type='file' name='excel'>
    </div>
</div>
@stop