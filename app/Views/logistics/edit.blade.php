@extends('common.form')
@section('formAction') {{ route('logistics.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="short_code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') ? old('short_code') : $model->short_code }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_type" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') ? old('logistics_type') : $model->logistics_type}}">
        </div>
        <div class="form-group col-lg-4">
            <label for="warehouse_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="warehouse_id" class="form-control" id="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}" {{$warehouse->id == $model->warehouse_id ? 'selected' : ''}}>
                        {{$warehouse->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_supplier_id">物流商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="logistics_supplier_id" class="form-control" id="logistics_supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" {{$supplier->id == $model->logistics_supplier_id ? 'selected' : ''}}>
                        {{$supplier->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') ? old('type') : $model->type}}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url" class="control-label">物流追踪网址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') ? old('url') : $model->url}}">
        </div>
        <div class="form-group col-lg-4">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking)
                    <option value="{{ $docking }}" {{ old('docking') ? (old('docking') == $docking ? 'selected' : '') : ($model->docking == $docking ? 'selected' : '') }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="species" class="control-label">种类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="express" {{ $model->species == 'express' ? 'checked' : '' }}>快递
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="packet" {{ $model->species == 'packet' ? 'checked' : '' }}>小包
                </label>
            </div>
        </div>
        <div class="form-group col-lg-4">
            <label for="is_enable" class="control-label">是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1" {{ $model->is_enable == '1' ? 'checked' : '' }}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="0" {{ $model->is_enable == '0' ? 'checked' : '' }}>否
                </label>
            </div>
        </div>
    </div>
@stop