@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stock.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class='form-group'>
        <label for='sku'>sku:</label>
            <input type='text' name='sku' class='form-control sku' placeholder='sku'>
        </div>
        <a href='javascript:' class='btn btn-info searchsku'>确认</a>
    </div>
    <table class='table'>
    <thead>
        <tr>ID</tr>
    </thead>
    </table>
@stop