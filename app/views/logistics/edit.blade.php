@extends('common.form')
@section('title') 编辑物流方式 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流方式</a></li>
        <li class="active"><strong>编辑物流方式</strong></li>
    </ol>
@stop

@section('formTitle') 编辑物流方式 @stop
@section('formAction') {{ route('logistics.update', ['id' => $logistics->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="short_code" class="control-label">物流方式简码</label>
        <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') ? old('short_code') : $logistics->short_code }}">
    </div>
    <div class="form-group">
        <label for="logistics_type" class="control-label">物流方式名称</label>
        <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') ? old('logistics_type') : $logistics->logistics_type}}">
    </div>
    <div class="form-group">
        <label for="species" class="control-label">种类</label>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="express" {{ $logistics->species == 'express' ? 'checked' : '' }}>快递
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="species" value="surface" {{ $logistics->species == 'surface' ? 'checked' : '' }}>小包
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="warehouse_id">仓库</label>
        <select name="warehouse_id" class="form-control">
            @foreach($warehouse as $warehouses)
                <option value="{{$warehouses->id}}" {{$warehouses->id == $logistics->warehouse_id ? 'selected' : ''}}>
                    {{$warehouses->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="logistics_supplier_id">物流商</label>
        <select name="logistics_supplier_id" class="form-control">
            @foreach($supplier as $suppliers)
                <option value="{{$suppliers->id}}" {{$suppliers->id == $logistics->logistics_supplier_id ? 'selected' : ''}}>
                    {{$suppliers->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="type" class="control-label">物流商物流方式</label>
        <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') ? old('type') : $logistics->type}}">
    </div>
    <div class="form-group">
        <label for="url" class="control-label">物流追踪网址</label>
        <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') ? old('url') : $logistics->url}}">
    </div>
    <div class="form-group">
        <label for="api_docking" class="control-label">API对接方式</label>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="物流api" {{ $logistics->api_docking == '物流api' ? 'checked' : '' }}>物流api
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="api_docking" value="号码池" {{ $logistics->api_docking == '号码池' ? 'checked' : '' }}>号码池
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="is_enable" class="control-label">是否启用</label>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="Y" {{ $logistics->is_enable == 'Y' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_enable" value="N" {{ $logistics->is_enable == 'N' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
@stop