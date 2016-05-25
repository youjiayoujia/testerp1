@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('remarkUpdate', ['id' => $model->id]) }} @stop
@section('formBody')
    <div class="panel panel-default">
        <div class="panel-heading">补充备注</div>
        <div class="panel-body">
            <div class="form-group col-lg-6">
                <label for="remark" class='control-label'>订单备注</label>
                <textarea class="form-control" rows="3" id="remark" name='remark'>{{ old('remark') ? old('remark') : $model->remark }}</textarea>
            </div>
        </div>
    </div>
@stop