@extends('common.form')
@section('formAction')  {{ route('purchaseAbnormal.update', ['id' => $model->id]) }}  @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type="hidden" name="update_userid" value="2"/>
@stop
 
 
 
 