@extends('common.form')
@section('formAction') {{ route('channel.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>订单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') }}">
        </div>
    </div>
@stop