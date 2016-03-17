@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('purchaseList.update', ['id' => $abnormal->id]) }}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="sku_id">sku_id:</label>
        {{$abnormal->sku_id}}
    </div>
     <div class="form-group col-lg-4">
        <label >sku_id:</label>
        {{$abnormal->productAbnormal->id}}
    </div>
    
@stop
 
 
 
 