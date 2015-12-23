@extends('common.form')
@section('title') 添加物流方式shippings @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsShipping.index') }}">物流方式shippings</a></li>
        <li class="active"><strong>添加物流方式shippings</strong></li>
    </ol>
@stop
<script type="text/javascript" src="{{ asset('js/pro_city.js') }}}"></script>

@section('formTitle') 添加物流方式shippings @stop
@section('formAction') {{ route('logisticsShipping.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group">
        <label for="short_code" class="control-label">物流方式简码</label>
        <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') }}">
    </div>
    <div class="form-group">
        <label for="logistics_type" class="control-label">物流方式名称</label>
        <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') }}">
    </div>
    <div class="form-group">
        <label for="species" class="control-label">种类</label>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="快递">快递
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="小包" checked>小包
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="warehouse" class="control-label">仓库</label>
        <input class="form-control" id="warehouse" placeholder="仓库" name='warehouse' value="{{ old('warehouse') }}">
    </div>
    <div class="form-group">
        <label for="logistics_id">物流商</label>
        <select name="logistics_id" class="form-control">
            @foreach($logisticsShippings as $shipping)
                <option value="{{$shipping->id}}" {{$shipping->id == old('$shipping->logisticsShipping->id') ? 'selected' : ''}}>{{$shipping->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="type_id">物流商物流方式</label>
        <select name="type_id" class="form-control">
            @foreach($logisticsShipping as $shipping)
                <option value="{{$shipping->id}}" {{$shipping->id == old('$shipping->logisticsShipping->id') ? 'selected' : ''}}>{{$shipping->type}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="url" class="control-label">物流追踪网址</label>
        <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') }}">
    </div>
    <div class="form-group">
        <label for="api_docking" class="control-label">API对接方式</label>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="物流api">物流api
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="号码池" checked>号码池
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="is_enable" class="control-label">是否启用</label>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="Y">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="N" checked>否
            </label>
        </div>
    </div>
@stop