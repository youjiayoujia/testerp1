@extends('common.form')
@section('formAction') {{ route('productMultiUpdate') }} @stop
@section('formBody')
    @foreach($languages as $name=>$language)  
        <label for="{{$language}}" >{{$language}}:</label>
        <div class="row">
            <?php $temp=$name."_name" ?>
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="{{$name}}_name" placeholder="标题" name='{{$name}}_name' value="{{ old($name.'_name')?old($name.'_name'):$model->productMultiOption->$temp }}">
            </div>
            <?php $temp=$name."_description" ?>
            <div class="form-group col-lg-4">  
                <input type='text' class="form-control" id="{{$name}}_description" placeholder="描述" name='{{$name}}_description' value="{{ old($name.'_description')?old($name.'_description'):$model->productMultiOption->$temp }}">
            </div>
            <?php $temp=$name."_keywords" ?>
            <div class="form-group col-lg-4">    
                <input type='text' class="form-control" id="{{$name}}_keywords" placeholder="关键词" name='{{$name}}_keywords' value="{{ old($name.'_keywords')?old($name.'_keywords'):$model->productMultiOption->$temp }}">
            </div>
        </div>
        
    @endforeach
    <input type="hidden" value="{{$id}}" name="product_id" >
@stop
