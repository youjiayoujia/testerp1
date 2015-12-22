@extends('common.form')
@section('title') 编辑物流方式shippings @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsShipping.index') }}">物流方式shippings</a></li>
        <li class="active"><strong>编辑物流方式shippings</strong></li>
    </ol>
@stop
<script type="text/javascript" src="{{ asset('js/pro_city.js') }}}"></script>

@section('formTitle') 编辑物流方式shippings @stop
@section('formAction') {{ route('logisticsShipping.update', ['id' => $logisticsShipping->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="short_code" class="control-label">物流方式简码</label>
        <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') ? old('short_code') : $logisticsShipping->short_code }}">
    </div>
    <div class="form-group">
        <label for="logistics_type" class="control-label">物流方式名称</label>
        <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') ? old('logistics_type') : $logisticsShipping->logistics_type}}">
    </div>
    <div class="form-group">
        <label for="species" class="control-label">种类</label>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="快递" {{ $logisticsShipping->species == '快递' ? 'checked' : '' }}>快递
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="小包" {{ $logisticsShipping->species == '小包' ? 'checked' : '' }}>小包
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="warehouse" class="control-label">仓库</label>
        <input class="form-control" id="warehouse" placeholder="仓库" name='warehouse' value="{{ old('warehouse') ? old('warehouse') : $logisticsShipping->warehouse}}">
    </div>
    <div class="form-group">
        <label for="logistics_id">物流商</label>
        <select name="logistics_id" class="form-control">
            @foreach($logisticsShippings as $shipping)
                <option value="{{$shipping->id}}" {{$shipping->id == $logisticsShipping->logistics_id ? 'selected' : ''}}>
                    {{$shipping->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="type_id">物流商物流方式</label>
        <select name="type_id" class="form-control">
            @foreach($logisticsShippingss as $shipping)
                <option value="{{$shipping->id}}" {{$shipping->id == $logisticsShipping->type_id ? 'selected' : ''}}>
                    {{$shipping->type}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="url" class="control-label">物流追踪网址</label>
        <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') ? old('url') : $logisticsShipping->url}}">
    </div>
    <div class="form-group">
        <label for="api_docking" class="control-label">API对接方式</label>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="物流api" {{ $logisticsShipping->api_docking == '物流api' ? 'checked' : '' }}>物流api
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="号码池" {{ $logisticsShipping->api_docking == '号码池' ? 'checked' : '' }}>号码池
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="is_enable" class="control-label">是否启用</label>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="Y" {{ $logisticsShipping->is_enable == 'Y' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="N" {{ $logisticsShipping->is_enable == 'N' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
@stop