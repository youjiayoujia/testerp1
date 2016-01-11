@extends('common.form')
@section('formAction') {{ route('logistics.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="short_code" class="control-label">物流方式简码</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_type" class="control-label">物流方式名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="warehouse_id">仓库</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="warehouse_id" class="form-control" id="warehouse_id">
            @foreach($warehouse as $warehouses)
                <option value="{{$warehouses->id}}" {{$warehouses->id == old('$warehouses->warehouse->id') ? 'selected' : ''}}>
                    {{$warehouses->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_supplier_id">物流商</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_supplier_id" class="form-control" id="logistics_supplier_id">
            @foreach($supplier as $suppliers)
                <option value="{{$suppliers->id}}" {{$suppliers->id == old('$suppliers->supplier->id') ? 'selected' : ''}}>
                    {{$suppliers->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="type" class="control-label">物流商物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="url" class="control-label">物流追踪网址</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="species" class="control-label">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="express" {{ old('species') ? (old('species') == 'express' ? 'checked' : '') : '' }}>快递
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="packet" {{ old('species') ? (old('species') == 'packet' ? 'checked' : '') : 'checked' }}>小包
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="api_docking" class="control-label">API对接方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
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
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">是否启用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
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