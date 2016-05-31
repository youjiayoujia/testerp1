@extends('common.form')
@section('formAction')  {{ route('batchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$item_ids}}' name="item_ids">
    <div class="form-group">
        <label for="sku">待编辑的sku：</label>
    </div>
    <div class="row">
        
        @foreach($skus as $sku)
            <div class="form-group col-md-1">
                <label for="sku">{{$sku->sku}}</label>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">状态</label>
            <select id="is_sale" class="form-control" name="is_sale">
                <option value="0">待售</option>
                <option value="1">在售</option>
                <option value="2">卖完下架</option>
                <option value="3">停产</option>
                <option value="4">试销</option>
                <option value="5">货源待定</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">重量</label>
            <input class="form-control" id="weight" placeholder="重量" name='weight' value="{{old('weight')}}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">英文名</label>
            <input class="form-control" id="name" placeholder="英文名" name='name' value="{{old('name')}}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">中文名</label>
            <input class="form-control" id="c_name" placeholder="中文名" name='c_name' value="{{old('c_name')}}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">参考成本</label>
            <input class="form-control" id="cost" placeholder="参考成本" name='cost' value="{{old('cost')}}">
        </div>
    </div>
@stop