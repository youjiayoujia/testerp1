
@extends('common.form')
@section('formAction') {{ route('beChosed') }} @stop
@section('formBody')
    <div class="form-group col-md-3"><label for="color">选择shop:</label>
        <select  class="form-control" name="channel_id">
            @foreach($channels as $channel)
                <option value="{{ $channel->id}}" >{{$channel->name}}</option>
            @endforeach
        </select>
    </div>

    <label for="product_model">product_model：</label>
    @foreach($data as $product)
     
    <div class="form-group">
        <input type="checkbox" name="product_ids[]"  value="{{$product['id']}}" >
        {{$product['model']}}
    </div>
    @endforeach
@stop