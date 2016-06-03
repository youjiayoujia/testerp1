@extends('common.form')
@section('formAction') {{ route('productMultiUpdate') }} @stop
@section('formBody')
    @foreach($channels as $channel)
        @foreach($languages as $name=>$language)  
            <label for="{{$language}}" >{{$channel->name}}{{$language}}:</label>
            <div class="row">
                <?php 
                    $temp=$name."_name";
                    $multiOption = $model->productMultiOption->where("channel_id",$channel->id)->first();
                ?>
                <div class="form-group col-lg-4">
                    <input type='text' class="form-control" id="{{$name}}_name" placeholder="标题" name='info[{{$channel->id}}][{{$name}}][{{$name}}_name]' value="{{ old($name.'_name')?old($name.'_name'):$multiOption->$temp }}">
                </div>
                <?php $temp=$name."_description" ?>
                <div class="form-group col-lg-4">  
                    <input type='text' class="form-control" id="{{$name}}_description" placeholder="描述" name='info[{{$channel->id}}][{{$name}}][{{$name}}_description]' value="{{ old($name.'_description')?old($name.'_description'):$multiOption->$temp }}">
                </div>
                <?php $temp=$name."_keywords" ?>
                <div class="form-group col-lg-4">    
                    <input type='text' class="form-control" id="{{$name}}_keywords" placeholder="关键词" name='info[{{$channel->id}}][{{$name}}][{{$name}}_keywords]' value="{{ old($name.'_keywords')?old($name.'_keywords'):$multiOption->$temp }}">
                </div>
            </div> 
        @endforeach
    @endforeach
    <input type="hidden" value="{{$id}}" name="product_id" >
@stop
