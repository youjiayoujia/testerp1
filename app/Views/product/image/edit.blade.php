@extends('common.form')
@section('formAction')  {{ route('productImage.update', ['id' => $model->id]) }}  @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-12">
        <label for="product_id">产品ID:</label>
        {{$model->product_id}}
    </div>
    <div class="form-group col-lg-12">
        <label for="type">图片类型：</label>
        {{ $model->type }}
    </div>
    <div class="form-group col-lg-12">
        <img src="{{ asset($model->src) }}" width="200px">
    </div>
    <div class="form-group col-lg-12">
        <label>更改图片：</label>
        <input name='image' type='file'/>
    </div>
@stop
 
 
 
 