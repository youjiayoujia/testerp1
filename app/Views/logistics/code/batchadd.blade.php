@extends('common.form')
@section('formAction') {{ route('logisticsCode.batchAddCode') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')

    <div class="col-lg-4">
        <strong>当前物流方式</strong>: {{ $logistic->logistics_type }}
    </div>
    <div class="col-lg-4">
        <strong>当前物流方式简码</strong>: {{ $logistic->short_code }}
    </div>
    <br />
    <div class="form-group col-lg-4">
        <label for="url" class="control-label">Select File</label>
        <input id="input-1" type="file" class="file">
    </div>
@stop
